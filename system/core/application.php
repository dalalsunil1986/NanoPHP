<?php

/**
 * The application loader. Executes controller action deduced by the {@link Router} class.
 * Shows the 404 page if controller does not exist or URL is not valid.
 */
class Application {

    const PAGE_NOT_FOUND_FILE_PATH = "public/404.html";
    const ERROR_PAGE_PATH = "public/error.php";

    /**
     * Set from the configuration <code>$config['default_controller']</code> value in <code>system/config.php</code> file.
     *
     * Hyperlink urls should be made as relative to this path. For example, (Application::basePath)."/controller/action/parameters".
     *
     * @var string The base url of the application without a trailing slash.
     */
    public static $basePath;

    /**
     * @var Session The singleton Session instance
     */
    private static $session;

    /**
     * @var Router The router instance
     */
    private static $router;

    /**
     * Runs the application, i.e., executes controller action deduced by the {@link Router} class.
     */
    public static function run() {
        try {
            self::getRouter()->loadControllerAndExecuteAction();
        } catch (InvalidURLException $e) {
            require_once self::PAGE_NOT_FOUND_FILE_PATH;
        } catch (ControllerNotFoundException $e) {
            require_once self::PAGE_NOT_FOUND_FILE_PATH;
        } catch (ControllerActionNotFoundException $e) {
            require_once self::ERROR_PAGE_PATH;
        } catch (Exception $e) {
            require_once self::ERROR_PAGE_PATH;
        }
    }

    /**
     * @return Session The Session instance
     */
    public static function getSession() {
        if (self::$session == null) {
            $sessionGetInstanceMethod = new ReflectionMethod('Session', 'getInstance');
            $sessionGetInstanceMethod->setAccessible(true);
            self::$session = $sessionGetInstanceMethod->invoke(null);
        }

        return self::$session;
    }

    /**
     * @return Router The Router instance
     */
    public static function getRouter() {
        if (self::$router == null) {
            self::$router = new Router();
        }

        return self::$router;
    }
}
