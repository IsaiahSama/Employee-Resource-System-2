<?php

namespace App\Models;

use Framework\Models\Model;
use App\Enums\Roles;


class User extends Model {
    public int $id;
    public string $username;
    public string $email;
    public string $password;
    public Roles $auth_level;

    public function __construct(int $id, string $username, string $email, string $password, Roles $auth_level=Roles::GUEST) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->auth_level = $auth_level;
    }

    public static function fromArray(array $data) {
        $id = $data['user_id'];
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $auth_level = match ((int) $data['auth_level']) {
            Roles::EMPLOYEE->value => Roles::EMPLOYEE,
            Roles::MANAGER->value => Roles::MANAGER,
            Roles::ADMIN->value => Roles::ADMIN,
            default => Roles::GUEST
        };

        return new User($id, $username, $email, $password, $auth_level);
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAuthLevel() {
        return $this->auth_level;
    }

    public function getAuthLevelValue() {
        return $this->auth_level->value;
    }

    public function getHashedPassword() {
        return $this->password;
    }
}