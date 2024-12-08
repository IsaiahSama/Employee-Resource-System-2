<?php

namespace App\Controllers;

use Framework\Controllers\ViewController;
use Framework\Views\Layout;

class IndexController implements ViewController{
    
    public function guard(){}

    public function index() {
        header("HTTP/1.0 200 OK");
        Layout::render("index", ['title' => "Home"]);
    }
}