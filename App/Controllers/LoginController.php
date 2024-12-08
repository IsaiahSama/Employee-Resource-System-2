<?php 

namespace App\Controllers;

use Framework\Controllers\ViewController;
use Framework\Views\Layout;
use App\Controllers\Responses\LoginResponse;

class LoginController implements ViewController{

    public function guard(){}

    public function index() {
        header("HTTP/1.0 200 OK");
        Layout::render('login');
    }

    public function handleSubmission(){
        LoginResponse::handleSubmission();
    }

    
}