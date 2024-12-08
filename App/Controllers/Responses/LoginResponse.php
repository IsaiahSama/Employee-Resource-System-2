<?php

namespace App\Controllers\Responses;

use Framework\Controllers\Response;
use App\Models\UserRepository;
use App\Views\Validators\LoginFormValidator;
use Framework\Controllers\SessionManager;
use Framework\Controllers\SessionValues;
use App\Enums\Roles;


class LoginResponse implements Response {

    public static function handleSubmission() {
        // Logic for handling the submissions
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $validator = new LoginFormValidator();
            
            if ($validator->validate(['email' => $email, 'password' => $password])){
                
                $user = UserRepository::findWhere('email', $email);
                SessionManager::set(SessionValues::USER_INFO->value, [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'auth_level' => $user->getAuthLevel()
                ]);
                
                // redirect to Dashboard page
                header("HTTP/1.0 200 OK");
                header("Location: /dashboard/" . match($user->getAuthLevelValue()) {
                    Roles::ADMIN->value => 'admin',
                    Roles::MANAGER->value => 'manager',
                    Roles::EMPLOYEE->value => 'employee',
                    default => ''
                    }
                );
                die();
            }
        }
        header("HTTP/1.0 400 Bad Request");
        header('Location: /login?error=Email or Password is invalid');
        die();
    }
}
