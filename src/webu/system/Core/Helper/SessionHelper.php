<?php declare(strict_types=1);

namespace webu\system\Core\Helper;

class SessionHelper
{

    /** @var array */
    private $session = array();

    /**
     * SessionHelper constructor.
     */
    public function __construct()
    {
        $this->startSession();
        $this->session = $_SESSION;
    }

    public function __destruct()
    {
        if($this->isSessionActive()) {
            session_write_close();
        }
    }


    /**
     * @param string $key
     * @param $value
     * @param bool $overrideExisting
     * @return bool
     */
    public function set(string $key, $value, bool $overrideExisting = true)
    {
        $this->startSession();
        if (isset($this->session[$key]) && $overrideExisting == false) {
            return false;
        }
        $_SESSION[$key] = $value;
        $this->session[$key] = $value;
        return true;
    }

    /**
     * @param string $key
     * @param bool $fallback
     * @return bool|mixed
     */
    public function get(string $key, $fallback = false)
    {
        if ($this->isSessionActive() == false || isset($this->session[$key]) == false) {
            return $fallback;
        }
        return $this->session[$key];
    }

    /**
     * @return bool
     */
    private function isSessionActive(): bool
    {
        return (session_status() == PHP_SESSION_ACTIVE);
    }

    /**
     * @return bool
     */
    private function startSession()
    {
        if ($this->isSessionActive() == false) {
            //$sessionStarted = session_start(['read_and_close'  => true]);
            $_SESSION = [];

            $sessionStarted = true;
            return $sessionStarted;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function destroySession()
    {
        if ($this->isSessionActive()) {
            session_destroy();
            $this->startSession();
            return true;
        }
        return false;
    }

}