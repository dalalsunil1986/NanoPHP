<?php

/**
 * Developed by Sharafat Ibn Mollah Mosharraf (sharafat_8271@yahoo.co.uk).
 *
 * Idea taken from the blog post "Writing a Bare-Minimum PHP Application" by Carson Myers.
 * http://typeandflow.blogspot.com/2011/04/writing-bare-minimum-php-application.html (last accessed: 01-MAY-2014)
 */

//All relative URLs will start from the application's top directory
chdir("../../");

//Start session
session_start();

//Include the system utility functions
require_once 'system/utils/utils.php';

//Register class auto-loader function
spl_autoload_register(function ($class) {
    if (class_exists($class, false)) {
        return;
    }

    $directoryIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.', FilesystemIterator::SKIP_DOTS));
    foreach ($directoryIterator as $filePath => $file) {
        if (str_ends_with(strtolower($filePath), strtolower("$class.php")) && strstr($filePath, '/view/') === false) {
            require_once $filePath;
        }
    }
});

//Include configurations that will be used by the application
require_once "config.php";

//Load hooks
global $hooks;
$hooks = array('pre_system' => array(),
               'pre_controller' => array(),
               'post_controller_constructor' => array(),
               'post_controller' => array(),
               'post_system' => array()
);
@require_once 'application/config/hooks.php';

//Execute pre_system hooks
Hook::execute($hooks['pre_system']);

//Set up application parameters and begin
Application::$basePath = $config['base_path'];
Application::run();

//Execute post_system hooks
Hook::execute($hooks['post_system']);
