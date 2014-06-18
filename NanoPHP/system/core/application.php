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
     * Runs the application, i.e., executes controller action deduced by the {@link Router} class.
     */
    public static function run() {
        try {
            Router::getInstance()->loadControllerAndExecuteAction();
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
}
