<?php

class Authentication {

    public function redirectToLoginIfNotAuthenticated() {
        $router = Application::getRouter();

        if ($router->getControllerName() === 'home' && $router->getActionName() === 'protectedPage') {
            header('location: ' . Application::$basePath . '/login');
            exit;
        }
    }
}
