<?php

/**
 * Hook function return type must be void. Anything returned from the hook function will be ignored.
 */
function pre_system_hook() {
    echo "<p><code>pre_system</code> hook executed!</p>";
}

function pre_controller_hook() {
    $router = Router::getInstance();

    if ($router->getActionName() === 'hookDemo') {
        echo "<p><code>pre_controller</code> hook executed!<br/>
                 Controller Name: {$router->getControllerName()}<br/>
                 Action Name: {$router->getActionName()}<br/>
                 Parameters: " . print_r($router->getParameters(), true) . "
              </p>";
    }
}

function post_controller_constructor_hook($dummyParameter) {
    if (Router::getInstance()->getActionName() === 'hookDemo') {
        echo "<p><code>post_controller_constructor</code> hook executed!<br/>
                 \$dummyParameter = $dummyParameter.
              </p>";
    }
}

function post_controller_hook() {
    $router = Router::getInstance();

    if ($router->getActionName() === 'hookDemo') {
        echo "<p><code>post_controller</code> hook executed!<br/>
                 View Name: {$router->getViewName()}<br/>
                 Model Map: " . print_r($router->getModelMap(), true) . "
              </p>";
    }
}

function post_system_hook() {
    if (Router::getInstance()->getActionName() === 'hookDemo') {
        echo "<p><code>post_system</code> hook executed!</p>";
    }
}
