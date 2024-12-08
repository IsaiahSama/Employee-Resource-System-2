<?php

namespace App\Controllers\Responses;

use Framework\Controllers\Response;
use App\Views\Validators\RegisterFormValidator;
use App\Views\Validators\Errors;
use App\Models\UserRepository;

class RegisterResponse implements Response {
    public static function handleSubmission() {
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            // Ensure each one has a value. In case XSS
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            self::registerUser($username, $email, $password);

        }

        else {
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(["error" => "Bad Request"]);
            die();
        }
    }

    public static function registerUser(string $username, string $email, string $password, int $role=1, bool $redirect=true) : string {
        $formFields = array('username' => $username, 'email' => $email, 'password' => $password, 'role' => $role);

        $validator = new RegisterFormValidator();

        $formError = $validator->validate($formFields);

        switch ($formError) {
            case Errors::FIELDS_MISSING:
                if ($redirect){
                    header("HTTP/1.0 400 Bad Request");
                    header('Location: /register?error=Missing fields in form');
                    die();
                }
                return "Missing fields in form";

            case Errors::EMAIL_IN_USE:
                if ($redirect){
                    header("HTTP/1.0 400 Bad Request");
                    header('Location: /register?error=Email already exists');
                    die();
                }

                return "Email already exists";

            case Errors::PASSWORD_TOO_WEAK:
                if ($redirect){
                    header("HTTP/1.0 400 Bad Request");
                    header('Location: /register?error=Your password is not secure enough. Try again.');
                    die();
                }

                return "Password is not strong enough!";

            case Errors::OK:
                break;
        }

            // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);  

        // Store the user information in the database
        $store_result  = UserRepository::create([
            'username' => $username,
            'email' => $email,
            'password' => $hashed_password,
            'auth_level' => $role
        ]);

        // On successful storage, redirect to login page

        if (!$store_result) {
            if ($redirect){
                header("HTTP/1.0 500 Internal Server Error");
                header("Location: /500?error=Could not register you at this time.");
                die();
            }

            return "Could not register user at this time.";
        }
        
        if ($redirect) {
            header("HTTP/1.0 200 OK");
            header('Location: /login');
            die();
        }
        return "";

    }

    public static function adminAddNewUser() {
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];

            if (!empty($username) && !empty($email) && !empty($password) && !empty($role)) {
                
                $error = self::registerUser($username, $email, $password, $role, false);
                if (!empty($error)) {
                    header("HTTP/1.0 400 Bad Request");
                    header("Location: /dashboard/admin?action=addUser&error=$error");
                    die();
                } else{
                    header("HTTP/1.0 200 OK");
                    header('Location: /dashboard/admin?action=viewUsers');
                    die();
                }
            } else {
                header("HTTP/1.0 400 Bad Request");
                header('Location: /dashboard/admin?action=addUser&error=Bad Request. Empty data.');
                die();
            }
        }

        else {
            header("HTTP/1.0 400 Bad Request");
            header('Location: /dashboard/admin?action=addUser&error=Bad Request. Missing data.');
            die();
        }
    }
}