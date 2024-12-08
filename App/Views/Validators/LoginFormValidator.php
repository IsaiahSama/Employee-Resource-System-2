<?php

namespace App\Views\Validators;

use Framework\Views\FormValidator;

use App\Models\User;
use App\Models\UserRepository;

class LoginFormValidator extends FormValidator {
    public function validate($form) : bool {
        if (!$this->allFieldsExist($form, array_keys($form))) {
            return false;
        }

        $user = UserRepository::findWhere('email', $form['email']);

        if (!$this->password_verify($user, $form['password'])) {
            return false;
        }
        return true;
    }

    private function password_verify(?User $user, string $password): bool {
        // Check if the given email exists in the database.

        if (!$user) {
            // If not, Return false.
            return false;
        }

        // Otherwise, get the password!
        $user_password = $user->getHashedPassword();

        // Compare the password!

        $password_match = password_verify($password, $user_password);

        // If the passwords match, return true.

        if ($password_match) {
            return true;
        }
        
        // Return false otherwise.
        return false;
        
    }
}