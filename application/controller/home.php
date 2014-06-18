<?php

/**
 * The file containing the controller class must be named as the lowercase of the controller class name.
 */
class Home {

    /**
     * Default action called when no action is specified.
     *
     * Return the view file path relative to the application/view directory. The framework will load the view.
     */
    public function index() {
        return 'home_index';
    }

    /**
     * An action with GET parameters. The GET parameters must be REST-ful. For example, if the URL is like
     * http://localhost/NanoPHP/home/sampleAction/val1/val2 then this action will be called with val1 and val2 as
     * arguments.
     *
     * If you need to pass some variables to the view file, return an array with the view file path as the first element
     * and an associative array of the variables. (This collection of variables is called the 'model' for the view).
     */
    public function sampleAction($arg1 = null, $arg2 = null) {
        return array('home_sampleAction',
                     array('arg1' => $arg1,
                           'arg2' => $arg2));
    }

    /**
     * An action with an authentication hook in place. The authentication hook will redirect the client to Login
     * controller. This method won't be called anyway. The auth hook is configured in application/config/hooks.php
     * and the hook action is defined in application/hook/auth.php.
     *
     * If you need to redirect to another URL from an action, simply return the redirect url with the prefix "redirect;".
     */
    public function protectedPage() {
        return 'redirect:' . Application::$basePath;
    }

    /**
     * Demonstrates session library usage.
     *
     * If you don't need a view file (for example, you're sending an attachment file to the client, or you are printing
     * the html right from the controller action), simply don't return anything.
     */
    public function sessionDemo() {
        $session = Session::getInstance();
        $success = assert(isset($session));
        echo $success ? '<code>Application::getSession()</code> working!<br/><br/>' : '';

        $session->setAttribute('attr1', 'value1');
        $success = assert($session->getAttribute('attr1') === 'value1');
        echo $success ? '<code>$session->setAttribute()</code> working!<br/><br/>
                         <code>$session->getAttribute()</code> working!<br/><br/>' : '';

        $session->removeAttribute('attr1');
        $success = assert($session->getAttribute('attr1') === null);
        echo $success ? '<code>$session->removeAttribute()</code> working!<br/><br/>' : '';

        $session->setFlashAttribute('flash1', 'val1');
        $success = assert($session->getFlashAttribute('flash1') === null); //Correct value can be found on next request.
        echo $success ? '<code>$session->setFlashAttribute()</code> working!<br/><br/>
                         <code>$session->getFlashAttribute()</code> working!<br/><br/>' : '';

        $session->invalidate();
        $success = assert(!isset($_SESSION));
        echo $success ? '<code>$session->invalidate()</code> working!' : '';
    }

    /**
     * Demonstrates how hooks are executed. The hooks related to this method are defined in application/hook/hook_demo.php,
     * and configured in application/config/hooks.php.
     */
    public function hookDemo($param1, $param2) {
        return array('home_sampleAction',
                     array('arg1' => $param1,
                           'arg2' => $param2));
    }
}
