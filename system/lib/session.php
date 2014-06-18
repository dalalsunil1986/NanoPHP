<?php

/**
 * A wrapper for session operations. Also Supports Flash data (i.e., data that persists only for the next HTTP request;
 * for example, displaying a success or error message as a result of an operation).
 */
class Session {

    private static $USER_DATA_KEY = "_user";
    private static $FLASH_DATA_KEY = "_flash";
    private static $FLASH_DATA_MARK_NEW = ":new:";
    private static $FLASH_DATA_MARK_OLD = ":old:";

    /**
     * @var Session
     */
    private static $sessionInstance;

    public static function getInstance() {
        if (self::$sessionInstance == null) {
            self::$sessionInstance = new Session();
            self::$sessionInstance->setup();
        }

        return self::$sessionInstance;
    }

    private function __construct() {
    }

    /**
     * Idea taken from CodeIgniter Session class:
     * https://github.com/EllisLab/CodeIgniter/blob/develop/system/libraries/Session/Session.php
     */
    private function setup() {
        //Set up session key for user data and flash data
        if (!array_key_exists(self::$USER_DATA_KEY, $_SESSION)) {
            $_SESSION[self::$USER_DATA_KEY] = array();
        }
        if (!array_key_exists(self::$FLASH_DATA_KEY, $_SESSION)) {
            $_SESSION[self::$FLASH_DATA_KEY] = array();
        }
        //Delete 'old' flashdata (from last request)
        $this->deleteOldFlashData();
        //Mark all 'new' flashdata as 'old' (data will be deleted before next request)
        $this->markNewFlashDataAsOld();
    }

    private function deleteOldFlashData() {
        foreach ($_SESSION[self::$FLASH_DATA_KEY] as $key => $value) {
            if (strpos($key, self::$FLASH_DATA_MARK_OLD)) {
                unset($_SESSION[self::$FLASH_DATA_KEY][$key]);
            }
        }
    }

    private function markNewFlashDataAsOld() {
        foreach ($_SESSION[self::$FLASH_DATA_KEY] as $name => $value) {
            $parts = explode(self::$FLASH_DATA_MARK_NEW, $name);
            if (is_array($parts) && count($parts) === 2) {
                $_SESSION[self::$FLASH_DATA_KEY][self::$FLASH_DATA_KEY . self::$FLASH_DATA_MARK_OLD . $parts[1]] = $value;
                unset($_SESSION[self::$FLASH_DATA_KEY][self::$FLASH_DATA_KEY . self::$FLASH_DATA_MARK_NEW . $parts[1]]);
            }
        }
    }

    /**
     * Adds/Replaces a flash attribute.
     *
     * @param $key
     * @param $value
     */
    public function setFlashAttribute($key, $value) {
        $_SESSION[self::$FLASH_DATA_KEY][self::$FLASH_DATA_KEY . self::$FLASH_DATA_MARK_NEW . $key] = $value;
    }

    /**
     * Returns the flash attribute value.
     *
     * @param $key
     * @return mixed|null The flash attribute if the key exists, null otherwise.
     */
    public function getFlashAttribute($key) {
        $actualKey = self::$FLASH_DATA_KEY . self::$FLASH_DATA_MARK_OLD . $key;

        if (array_key_exists($actualKey, $_SESSION[self::$FLASH_DATA_KEY])) {
            return $_SESSION[self::$FLASH_DATA_KEY][self::$FLASH_DATA_KEY . self::$FLASH_DATA_MARK_OLD . $key];
        } else {
            return null;
        }
    }

    /**
     * Adds/Replaces an attribute.
     *
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value) {
        $_SESSION[self::$USER_DATA_KEY][$key] = $value;
    }

    /**
     * Returns the attribute value.
     *
     * @param $key
     * @return null
     */
    public function getAttribute($key) {
        if (array_key_exists($key, $_SESSION[self::$USER_DATA_KEY])) {
            return $_SESSION[self::$USER_DATA_KEY][$key];
        } else {
            return null;
        }
    }

    /**
     * Removes an attribute from session.
     *
     * @param $key
     */
    public function removeAttribute($key) {
        unset($_SESSION[self::$USER_DATA_KEY][$key]);
    }

    /**
     * Destroys the session.
     */
    public function invalidate() {
        unset($_SESSION);
        session_destroy();
    }
}
