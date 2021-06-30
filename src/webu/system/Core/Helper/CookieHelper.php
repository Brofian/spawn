<?php declare(strict_types=1);

namespace webu\system\Core\Helper;

use webu\system\Core\Custom\Debugger;

class CookieHelper
{

    /** @var array */
    private $cookies = array();

    /**
     * CookieHelper constructor.
     */
    public function __construct()
    {
        $this->cookies = $_COOKIE;
    }


    /**
     * @param string $key
     * @param string $value
     * @param bool $overrideExisting
     * @param string $path
     * @param int $expires
     * @param bool $secure
     * @return bool
     */
    public function set(string $key, string $value, bool $overrideExisting = true, string $path = "/", int $expires = 0, bool $secure = false, bool $httpOnly = false, string $sameSite = "Strict")
    {
        if (isset($this->cookies[$key]) && $overrideExisting == false) return false;

        $options = [
            "expires" => $expires,
            "path" => $path,
            "domain" => $_SERVER["HTTP_HOST"],
            "secure" => $secure,
            "httponly" => $httpOnly,
            "samesite" => $sameSite,
        ];

        setcookie($key, $value, $options);
        $this->cookies[$key] = $value;
        $_COOKIE[$key] = $value;
        return false;
    }


    /**
     * @param string $key
     * @param bool $fallback
     * @return bool|mixed
     */
    public function get(string $key, bool $fallback = false)
    {
        if (isset($this->cookies[$key])) return $this->cookies[$key];
        return $fallback;
    }

    /**
     * @return array
     */
    public function getCookies() : array {
        return $this->cookies;
    }
}