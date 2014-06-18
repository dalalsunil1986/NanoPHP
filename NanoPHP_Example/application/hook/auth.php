<?php

class Authentication {

    public function redirectToLoginIfNotAuthenticated() {
        $router = Router::getInstance();

        if ($router->getControllerName() === 'home' && $router->getActionName() === 'protectedPage') {
            header('location: ' . Application::$basePath . '/login');
            exit;
        }
    }
}
