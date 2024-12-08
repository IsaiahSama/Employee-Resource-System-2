<?php

namespace App\Helpers;

use App\Enums\Roles;

class AuthHelper {

    public static function hasAccess($user, $criteria){
        
        foreach ($criteria as $role) {
            if ($user['auth_level']->value == $role) {
                return true;
            }
        }
        return false;
    }

    public static function forAdmin() {
        return [Roles::ADMIN->value];
    }
    public static function forManager() {
        return [Roles::MANAGER->value, Roles::ADMIN->value];
    }
    public static function forEmployee() {
        return [Roles::EMPLOYEE->value, Roles::MANAGER->value, Roles::ADMIN->value];
    }
    public static function forAll() {
        return [Roles::GUEST->value, Roles::EMPLOYEE->value, Roles::MANAGER->value, Roles::ADMIN->value];
    }

    public static function roleToValue($user){
        return $user['auth_level']->value;
    }
}