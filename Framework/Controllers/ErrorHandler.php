<?php

namespace Framework\Controllers;

final class ErrorHandler {

    public static function errorHandler($errno, $errstr, $errfile, $errline) {
        echo "<b>Error:</b> [$errno] $errstr - $errfile:$errline";
        exit;
    }
}