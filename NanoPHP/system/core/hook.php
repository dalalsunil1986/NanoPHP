<?php

/**
 * Executes hooks.
 */
class Hook {

    /**
     * Executes the hook.
     *
     * @param array $hookCategory The array of hooks of a particular category (e.g. pre_system etc.) to be executed.
     */
    public static function execute(array $hookCategory) {
        foreach ($hookCategory as $hook) {
            self::executeHook($hook['filePath'],
                          array_key_exists('class', $hook) ? $hook['class'] : null,
                          $hook['function'],
                          array_key_exists('params', $hook) ? $hook['params'] : array());
        }
    }

    private static function executeHook($filePath, $className, $functionName, array $arguments) {
        $file = "application/hook/$filePath";
        if (!file_exists($file)) {
            throw new RuntimeException("Hook file '$filePath' not found in application/hook/ directory.");
        }
        require_once $file;

        if (isset($className) && !empty($className)) {
            $obj = new $className();

            if (!method_exists($obj, $functionName)) {
                throw new RuntimeException("Hook method '$functionName' does not exist in class '$className'.");
            }

            call_user_func_array(array($obj, $functionName), $arguments);
        } else {
            if (!function_exists($functionName)) {
                throw new RuntimeException("Hook function '$functionName' does not exist in file '$file'.");
            }

            call_user_func_array($functionName, $arguments);
        }
    }
}
