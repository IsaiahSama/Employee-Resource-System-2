<?php 

namespace App\Controllers;

use Framework\Controllers\ViewController;
use Framework\Views\Layout;


class ErrorController implements ViewController {

    public function guard(){
        
    }

    public function index() {
        header("HTTP/1.0 200 OK");
        header("Location: /");
    }

    public function error401() {
        Layout::render('errors/401', ['title' => "Unauthorized"]);
    }
    
    public function error404() {
        Layout::render('errors/404', ['title' => "Page Not Found"]);
    }


    public function error500() {
        Layout::render('errors/500', ['title' => "Internal Server Error"]);
    }
}