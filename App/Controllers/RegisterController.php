<?php 

namespace App\Controllers;

use Framework\Controllers\ViewController;
use Framework\Views\Layout;
use App\Controllers\Responses\RegisterResponse;

class RegisterController implements ViewController{

    public function guard(){}

    public function index() {
        header("HTTP/1.0 200 OK");
        Layout::render("register");
    }

    public function handleSubmission() {
        RegisterResponse::handleSubmission();
    }

    public function adminAddNewUser() {
        RegisterResponse::adminAddNewUser();
    }

}