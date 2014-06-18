<?php

/**
 * Loads a controller and executes its specific action deduced from URL.
 *
 * Throws {@link InvalidURLException} if the controller or action segment in the URL contains invalid characters.
 * See {@link Router::URL_SEGMENT_PATTERN} for valid controller and action pattern.
 *
 * Throws {@link ControllerNotFoundException} if the deduced controller does not exist.
 *
 * Throws {@link ControllerActionNotFoundException} if the deduced controller action does not exist.
 */
class Router {

    /**
     * Pattern for valid controller and action names.
     */
    const URL_SEGMENT_PATTERN = '/^[a-zA-Z0-9_]*$/';

    /**
     * @var Router
     */
    private static $routerInstance;

    /**
     * @var string
     */
    private $controllerName;

    /**
     * @var string
     */
    private $actionName;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var object
     */
    private $controllerObject;

    /**
     * @var string
     */
    private $viewName;

    /**
     * @var array
     */
    private $modelMap;

    /**
     * @return string
     */
    public function getControllerName() {
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getActionName() {
        return $this->actionName;
    }

    /**
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }

    /**
     * @return object
     */
    public function getControllerObject() {
        return $this->controllerObject;
    }

    /**
     * @return string
     */
    public function getViewName() {
        return $this->viewName;
    }

    /**
     * @return array
     */
    public function getModelMap() {
        return $this->modelMap;
    }

    /**
     * @return Router
     */
    public static function getInstance() {
        if (self::$routerInstance == null) {
            self::$routerInstance = new Router();
        }

        return self::$routerInstance;
    }

    private function __construct() {
    }

    /**
     * Executes an action of a controller based on URL segments.
     *
     * @param string $url The URL to be parsed for a controller and an action
     *
     * @throws InvalidURLException
     * @throws ControllerActionNotFoundException
     * @throws UnexpectedValueException
     */
    public function loadControllerAndExecuteAction($url = null) {
        list($this->controllerName, $this->actionName, $this->parameters) = self::urlElements($url);

        global $hooks;

        //Execute pre_controller hooks
        Hook::execute($hooks['pre_controller']);

        //Load controller
        $this->controllerObject = self::loadController($this->controllerName);

        //Execute post_controller_constructor hooks
        Hook::execute($hooks['post_controller_constructor']);

        if (!method_exists($this->controllerObject, $this->actionName)) {
            throw new ControllerActionNotFoundException(
                "Action '$this->actionName' not found in controller '$this->controllerName'.");
        }

        $modelAndView = call_user_func_array(array($this->controllerObject, $this->actionName), $this->parameters);

        if (is_string($modelAndView)) { //Only view name is provided
            $this->viewName = $modelAndView;
        } else if (is_array($modelAndView) && is_array($modelAndView[1])) { //A view name and an array of key-value pair is provided
            $this->viewName = $modelAndView[0];
            $this->modelMap = $modelAndView[1];
        } else if (is_null($modelAndView)) { //Most likely the controller action has output what needed to be output
            return;
        } else {
            throw new UnexpectedValueException('Invalid action return-value type: ' . gettype($modelAndView)
                                               . '. Expected either a string with a view name or an array with a'
                                               . ' view name and an associative array representing the model.');
        }

        //Execute post_controller hooks
        Hook::execute($hooks['post_controller']);

        //Load view
        if (str_starts_with($this->viewName, 'redirect:')) {
            header('location: ' . str_replace('redirect:', '', $this->viewName));
        } else {
            //Set the variables to be used in the view
            if (isset($this->modelMap)) {
                foreach ($this->modelMap as $key => $value) {
                    /*
                        Make the variable global so that it can be used inside a function. Note that the global
                        declaration must come before the assignment so that it works on PHP 5.3+. See:
                        http://julianhigman.com/blog/2010/11/05/php-5-3-and-the-global-keyword/
                    */
                    global $$key;
                    $$key = $value;
                }
            }

            require_once "application/view/$this->viewName.php";
        }
    }

    /**
     * Gets the elements necessary to load and execute a controller action from the URL, returning them as a 3-item
     * array: (controller, action, parameters).
     *
     * @param string $url The URL from which the controller and action is to be deduced.
     *                    If null, $_SERVER["REQUEST_URI"] is used.
     *
     * @return array Array Containing controller (string), action (string) and parameters (array) deduced from the URL.
     * @throws InvalidURLException If the controller or action segment in the URL contains invalid characters.
     */
    protected static function urlElements($url = null) {
        if ($url === null) {
            $url = $_SERVER["REQUEST_URI"];
        }

        //If the application isn't located at the top directory, get rid of the base part of the directory for processing
        $applicationURL = trim(str_replace_first(Application::$basePath, '', $url), '/');
        $urlParts = explode("/", $applicationURL);

        //If the "/" was requested, explode will return a 1-element array with an empty element.
        //Change it to an empty array for the next step
        if (count($urlParts) == 1 && empty($urlParts[0])) {
            $urlParts = array();
        }

        self::assertValidControllerAndActionName(array_slice($urlParts, 0, 2));

        //Build the array of URL elements
        global $config;
        $controller = (count($urlParts) > 0) ? $urlParts[0] : $config['default_controller'];
        $action = (count($urlParts) > 1) ? $urlParts[1] : "index";
        $parameters = array_slice($urlParts, 2);

        return array($controller, $action, $parameters);
    }

    /**
     * Makes sure that the controller and action parts of a URL matches a pattern, or throws an exception.
     * See {@link Router::URL_SEGMENT_PATTERN} for the valid pattern for controller and action names.
     *
     * @param array $urlParts An array containing the controller and action parts of a URL
     *
     * @throws InvalidURLException If the url part does not match the required pattern.
     */
    private static function assertValidControllerAndActionName(array $urlParts) {
        foreach ($urlParts as $part) {
            if (preg_match(self::URL_SEGMENT_PATTERN, $part) == 0) {
                throw new InvalidURLException("Invalid controller or action name: $part");
            }
        }
    }

    /**
     * Loads a controller.
     *
     * @param $controllerName string The name of the controller to be loaded
     *
     * @return object The controller instance
     * @throws ControllerNotFoundException If the controller does not exist
     */
    protected static function loadController($controllerName) {
        $controllerFile = "application/controller/$controllerName.php";

        if (!file_exists($controllerFile)) {
            throw new ControllerNotFoundException("Controller file '$controllerName.php' does not exist.");
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            throw new ControllerNotFoundException("No class named '$controllerName' exists in $controllerName.php.");
        }

        return new $controllerName();
    }
}


/**
 * Thrown when the user requested a page with an invalid URL.
 */
class InvalidURLException extends Exception {
}


/**
 * Thrown when a requested controller cannot be found
 */
class ControllerNotFoundException extends Exception {
}


/**
 * Thrown when a requested controller action cannot be found
 */
class ControllerActionNotFoundException extends Exception {
}
