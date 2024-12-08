<?php

namespace App\Controllers\Responses;

use Framework\Controllers\Response;
use App\Models\UserRepository;


class UserResponse implements Response
{
    public static function handleSubmission()
    {}

    public static function deleteUser(){
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            UserRepository::delete($id);
            header("HTTP/1.0 201 OK");
            header('Location: /dashboard/admin?action=viewUsers');
            die();
        }
    }

    public static function updateUser(){
        if (isset($_GET['id']) && isset($_POST['auth_level'])) {
            $id = $_GET['id'];
            $auth_level = $_POST['auth_level'];
            UserRepository::update($id, "auth_level",$auth_level);
            header("HTTP/1.0 201 OK");
            header('Location: /dashboard/admin?action=viewUsers');
            die();
        }
    }

}