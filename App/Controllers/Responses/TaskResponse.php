<?php

namespace App\Controllers\Responses;

use App\Enums\Status;
use App\Views\Validators\CreateTaskFormValidator;
use App\Views\Validators\Errors;
use App\Models\TaskRepository;
use App\Enums\Roles;
use App\Helpers\AuthHelper;

use Framework\Controllers\SessionManager;
use Framework\Controllers\SessionValues;
use Framework\Controllers\Authentication;
use Framework\Controllers\ViewController;
use Framework\Views\Layout;
use Framework\Controllers\Response;

class TaskResponse implements Response {
    public static function handleSubmission() {
        $current_user = SessionManager::get(SessionValues::USER_INFO->value);

        if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['assigned']) && isset($_POST['dueDate'])) {

            $title = $_POST['title'];
            $description = $_POST['description'];
            $assigned = $_POST['assigned'];
            $dueDate = $_POST['dueDate'];
            $status = Status::PENDING->value;

            $success = TaskRepository::createTask($title, $description, $status, $assigned, $current_user['id'], $dueDate);
            $success_route = '/dashboard/' . match (Authentication::getSessionAuth(SessionValues::USER_INFO->value, function ($user) {
                return AuthHelper::roleToValue($user);
            }
            )) {

                Roles::ADMIN->value => 'admin',
                Roles::MANAGER->value => 'manager',
                default => 'manager'
            } . '?action=viewTasks';

            if ($success) {
                header("HTTP/1.0 200 OK");
                header("Location: $success_route");
                die();
            } else {
                header("HTTP/1.0 400 Bad Request");
                echo json_encode(["error" => "Bad Request"]);
                die();
            }
        } else {
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(["error" => "Bad Request"]);
            die();
        }
    }

    public static function updateTask(){
        // Ensure person either owns the task, has the task assigned to them, or is an admin.

        $task = TaskRepository::find($_POST['id']);
        $current_user = SessionManager::get(SessionValues::USER_INFO->value);

        if (empty($task)) {
            header("HTTP/1.0 400 Bad Request");
            Layout::render('errors/404', ['title' => "Task Not Found"]);
            die();
        }

        $is_authorized = ($task->assigned_to == $current_user['id'] || $task->created_by == $current_user['id'] || $current_user['auth_level'] == Roles::ADMIN);

        if (!($is_authorized)) {
            header("HTTP/1.0 401 Unauthorized");
            Layout::render('errors/401', ['title' => "Access Denied"]);
            die();
        }

        $roleAccess = Authentication::getSessionAuth(SessionValues::USER_INFO->value, function ($user) {
            return AuthHelper::roleToValue($user);
        });

        if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['assigned']) && isset($_POST['dueDate']) && isset($_POST['status']) && isset($_POST['id'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $assigned = $_POST['assigned'];
            $dueDate = $_POST['dueDate'];
            $status = $_POST['status'];
            $comments = $_POST['comments'] ?? "";
            $id = $_POST['id'];

            $success = TaskRepository::updateTask($id, $title, $description, $status, $assigned, $dueDate, $comments);
            $success_route = '/dashboard/' . match ($roleAccess) {
                Roles::ADMIN->value => 'admin',
                Roles::MANAGER->value => 'manager',
                Roles::EMPLOYEE->value => 'employee',
                default => 'employee'
            } . '?action=viewTasks';

            if ($success) {
                header("HTTP/1.0 200 OK");
                header("Location: $success_route");
                die();
            } else {
                header("HTTP/1.0 400 Bad Request");
                echo json_encode(["error" => "Bad Request"]);
                die();
            }
        } else {
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(["error" => "No Data!"]);
            die();
        }
    }
}