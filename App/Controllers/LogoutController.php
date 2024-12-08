<?php

namespace App\Controllers;

use Framework\Controllers\ViewController;
use Framework\Controllers\SessionManager;

class LogoutController implements ViewController{
    public function guard(){}

    public function index() {
        header("HTTP/1.0 200 OK");
        header("Location: /");
    }

    public function logout() {

        SessionManager::restart();
        header("HTTP/1.0 200 OK");
        header("Location: /login?success=Successfully logged out");
        die();
    }
}