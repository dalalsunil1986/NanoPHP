<?php

/**
 * Define all the hooks in this file. You can use the $config global variable here. Hooks belonging to the same
 * category will be executed in the order they're defined.
 *
 * Available hooks:
 * - pre_system
 *      Called very early during system execution. Only the configs and hooks have been loaded at this point. No
 *      routing or other processes have happened.
 * - pre_controller
 *      Called immediately prior to any of the controllers being called. Routing has been done.
 * - post_controller_constructor
 *      Called immediately after the controller is instantiated, but prior to any action method calls happening.
 * - post_controller
 *      Called immediately after the controller is fully executed, but prior to loading the view file (in case a view
 *      file name is returned from the controller).
 * - post_system
 *      Called after the final rendered page is sent to the browser, at the end of system execution after the finalized
 *      data is sent to the browser.
 *
 *
 * The array elements defined for a hook is:
 * - class (optional)
 *      The class that contains the method to be executed as the hook.
 * - function (required)
 *      The function (in case no class is mentioned), or method (that the class contains) to be executed as the hook.
 * - filePath (required)
 *      The name of the file containing the class/function relative to the application/hook directory
 * - params (optional)
 *      The parameters to be passed as the function arguments. Must be inside an array even if there's only one parameter.
 */

$hooks['pre_controller'][] = array(
    'class' => 'Authentication',
    'function' => 'redirectToLoginIfNotAuthenticated',
    'filePath' => 'auth.php',
    'params' => array('dummyValue')
);

$hooks['pre_system'][] = array(
    'function' => 'pre_system_hook',
    'filePath' => 'hook_demo.php'
);

$hooks['pre_controller'][] = array(
    'function' => 'pre_controller_hook',
    'filePath' => 'hook_demo.php'
);

$hooks['post_controller_constructor'][] = array(
    'function' => 'post_controller_constructor_hook',
    'filePath' => 'hook_demo.php',
    'params' => array('dummyValue')
);

$hooks['post_controller'][] = array(
    'function' => 'post_controller_hook',
    'filePath' => 'hook_demo.php'
);

$hooks['post_system'][] = array(
    'function' => 'post_system_hook',
    'filePath' => 'hook_demo.php'
);
