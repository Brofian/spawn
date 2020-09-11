<?php

namespace webu\system\Core\Helper;

class CookieHelper {

    /** @var array  */
    private $cookies = array();

    public function __construct()
    {
        $this->cookies = $_COOKIE;
    }


    /**
     * @param string $key
     * @param string $value
     * @param bool $overrideExisting
     * @return bool
     */
    public function set(string $key, string $value, bool $overrideExisting) {
        if(isset($this->cookies[$key]) && $overrideExisting == false) return false;

        $this->cookies[$key]    = $value;
        $_COOKIE[$key]          = $value;
        return false;
    }


    /**
     * @param string $key
     * @param bool $fallback
     * @return bool|mixed
     */
    public function get(string $key, bool $fallback = false) {
        if(isset($this->cookies[$key])) return $this->cookies[$key];
        return $fallback;
    }

}