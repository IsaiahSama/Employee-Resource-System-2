<?php

namespace Framework\Controllers;

final class SessionManager {
    public static function startOrIgnore() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function restart(){
        if (session_status() === PHP_SESSION_ACTIVE) {
            self::destroy();
            self::startOrIgnore();
        }
    }

    public static function destroy() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
