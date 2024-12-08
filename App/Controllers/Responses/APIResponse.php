<?php

namespace App\Controllers\Responses;

use App\Views\Validators\LoginFormValidator;

use Framework\Controllers\Response;

class APIResponse implements Response {

    public static function handleSubmission()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['email']) || !isset($_POST['password'])) {
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(["error" => "Was expecting fields called `email` and `password`."]);
            die();
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $validator = new LoginFormValidator();

        if (!$validator->validate(['email' => $email, 'password' => $password])) {
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(["error" => "Either username or password is incorrect."]);
            die();
        }

        echo json_encode(['success' => "logged in successfully!"]);
    }
}