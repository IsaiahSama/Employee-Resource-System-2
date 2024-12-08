<?php

namespace App\Controllers;

use App\Enums\Roles;
use App\Helpers\AuthHelper;
use App\Models\TaskRepository;
use App\Models\UserRepository;
use App\Controllers\Responses\TaskResponse;

use Framework\Controllers\SessionManager;
use Framework\Controllers\SessionValues;
use Framework\Controllers\ViewController;
use Framework\Controllers\Authentication;
use Framework\Views\Layout;

class TaskController implements ViewController
{

    public function guard(){
        Authentication::hasAccess(SessionValues::USER_INFO->value, function ($string, $criteria) {
            return AuthHelper::hasAccess($string, $criteria);
        }, AuthHelper::forManager());
    }

    public function secondaryGuard(){
        Authentication::hasAccess(SessionValues::USER_INFO->value, function ($string, $criteria) {
            return AuthHelper::hasAccess($string, $criteria);
        }, AuthHelper::forEmployee());
    }

    public function index()
    {

        $this->guard();

        $current_user = SessionManager::get(SessionValues::USER_INFO->value);
        $auth_level = $current_user['auth_level']->value;

        $taskable_users = [];

        if ($auth_level == Roles::ADMIN->value) {
            $taskable_users = UserRepository::getAll();
        } else {
            $taskable_users = UserRepository::findWhereNot("auth_level", Roles::ADMIN->value);
        }

        header("HTTP/1.0 200 OK");
        Layout::render("create-task", ['title' => "Create Task", 'users' => $taskable_users]);
    }

    public function createTask()
    {
        $this->guard();

        TaskResponse::handleSubmission();
    }

    public function edit()
    {
        $this->secondaryGuard();

        $task = TaskRepository::find($_GET['id']);
        $current_user = SessionManager::get(SessionValues::USER_INFO->value);

        if (empty($task)) {
            header("HTTP/1.0 400 Bad Request");
            Layout::render('errors/404', ['title' => "Task Not Found"]);
            die();
        }

        $is_authorized = ($task->assigned_to == $current_user['id'] || $task->created_by == $current_user['id'] || $current_user['auth_level'] == Roles::ADMIN);

        if (!$is_authorized) {
            header("HTTP/1.0 401 Unauthorized");
            Layout::render('errors/401', ['title' => "Access Denied"]);
            die();
        }

        $auth_level = $current_user['auth_level']->value;

        $taskable_users = [];

        if ($auth_level == Roles::ADMIN->value) {
            $taskable_users = UserRepository::getAll();
        } else {
            $taskable_users = UserRepository::findWhereNot("auth_level", Roles::ADMIN->value);
        }

        header("HTTP/1.0 200 OK");
        Layout::render("tasks/edit", ['title' => "Edit Task", 'task' => $task, 'users' => $taskable_users]);
    }

    public function updateTask()
    {

        $this->secondaryGuard();

        TaskResponse::updateTask();
    }

    public function filterTasks()
    {
        $this->guard();

        // $role = Authentication::getSessionAuth( SessionValues::USER_INFO->value, function ($user) {
        //     return AuthHelper::roleToValue($user);
        // });

        // $is_admin = $role == Roles::ADMIN->value;

        if (isset($_GET['filter']) && !empty($_GET['filter'])) {
            $filter = $_GET['filter'];
            if ($filter == "ALL" || $filter == "PENDING" || $filter == "PROGRESS" || $filter == "COMPLETED") {
                header("HTTP/1.0 200 Ok");
                header("Location: /dashboard/manager?action=filterTasks&filter=$filter");
                die();
            }
        }
        header("HTTP/1.0 400 Bad Request");
        echo json_encode(["error" => "Bad Request"]);
        die();
    }
}
