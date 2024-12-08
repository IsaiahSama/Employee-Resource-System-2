<?php

namespace Framework\Controllers;

final class Authentication {

    
    /**
     * $validateFunc is supposed to be a function that takes 2 arguments, a string, and an array of criteria. This should return a boolean
     */
    public static function hasAccess($sessionKey, $validateFunc, $expectedValues, $redirectErrorPath="/401"){
        $sessionValue = SessionManager::get($sessionKey);
        if ($sessionValue == null || !$validateFunc($sessionValue, $expectedValues)) {
            header("HTTP/1.0 401 Unauthorized");
            header("Location: $redirectErrorPath");
            die();
        }
    }

    public static function getSessionAuth($sessionKey, $converterFunc=null) {
        $sessionValue = SessionManager::get($sessionKey);

        if ($sessionValue == null) {
            return null;
        }

        if ($converterFunc != null) {
            return $converterFunc($sessionValue);
        }
        return $sessionValue;
    }
}

