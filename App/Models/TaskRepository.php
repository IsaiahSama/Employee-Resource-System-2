<?php

namespace App\Models;

use Framework\Models\DatabaseSingleton;
use Framework\Models\Repository;

use App\Models\Task;

class TaskRepository extends Repository {
    public static string $table = 'Tasks';
    
    public static function getAll() {
        // https://www.w3schools.com/php/func_mysqli_fetch_all.asp

        $conn = DatabaseSingleton::getInstance();

        $sql = "SELECT * FROM " . self::$table;

        $query = $conn->query($sql);

        $raw_tasks = $query->fetch_all(MYSQLI_ASSOC);
        $tasks = [];
        
        foreach ($raw_tasks as $raw_task) {
            $tasks[] = Task::fromArray($raw_task);
        }
        return $tasks;
    }

    public static function find($id) {
        $conn = DatabaseSingleton::getInstance();

        $sql = "SELECT * FROM " . self::$table . " WHERE task_id = $id";
        $query = $conn->query($sql);
        $raw_task = $query->fetch_assoc();

        if (!$raw_task) {
            return null;
        }

        return Task::fromArray($raw_task);
    }


    public static function findWhere($key, $value) {
        $conn = DatabaseSingleton::getInstance();

        $sql = "SELECT * FROM " . self::$table . " WHERE $key = $value";

        $query = $conn->query($sql);

        $raw_tasks = $query->fetch_all(MYSQLI_ASSOC);
        $tasks = [];
        
        foreach ($raw_tasks as $raw_task) {
            $tasks[] = Task::fromArray($raw_task);
        }
        return $tasks;
    }

    public static function getTasksByOwnerId(int $owner_id) {
        $conn = DatabaseSingleton::getInstance();

        $sql = "SELECT * FROM " . self::$table . " WHERE created_by = $owner_id OR assigned_to = $owner_id ORDER BY task_id DESC";

        $query = $conn->query($sql);

        $raw_tasks = $query->fetch_all(MYSQLI_ASSOC);
        $tasks = [];
        
        foreach ($raw_tasks as $raw_task) {
            $tasks[] = Task::fromArray($raw_task);
        }
        return $tasks;
    }

    public static function getTasksByUserId(int $user_id) {
        $conn = DatabaseSingleton::getInstance();

        $sql = "SELECT * FROM " . self::$table . " WHERE assigned_to = $user_id ORDER BY task_id DESC";

        $query = $conn->query($sql);

        $raw_tasks = $query->fetch_all(MYSQLI_ASSOC);
        $tasks = [];
        
        foreach ($raw_tasks as $raw_task) {
            $tasks[] = Task::fromArray($raw_task);
        }
        return $tasks;
    }

    public static function createTask(string $title, string $description, string $status, int $assigned_to, int $created_by, string $dueDate, string $comments = '') {
        $conn = DatabaseSingleton::getInstance();

        $sql = "INSERT INTO " . self::$table . " (title, description, status, assigned_to, created_by, due_date, comments) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmmt = $conn->prepare($sql);
        $stmmt->bind_param("sssiiss", $title, $description, $status, $assigned_to, $created_by, $dueDate, $comments);
        return $stmmt->execute();
    }

    public static function create($data) {
        
        return self::createTask($data['title'], $data['description'], $data['status'], $data['assigned_to'], $data['created_by'], $data['due_date'], $data['comments']);
    }

    public static function updateTask(int $id, string $title, string $description, string $status, int $assigned_to, string $dueDate, string $comments = '') {
        $conn = DatabaseSingleton::getInstance();

        $sql = "UPDATE " . self::$table . " SET title = ?, description = ?, status = ?, assigned_to = ?, due_date = ?, comments = ? WHERE task_id = $id";
        $stmmt = $conn->prepare($sql);
        $stmmt->bind_param("sssiss", $title, $description, $status, $assigned_to, $dueDate, $comments);
        return $stmmt->execute();
    }

    public static function update($id, $data, $_) {
        return self::updateTask($id, $data['title'], $data['description'], $data['status'], $data['assigned_to'], $data['due_date'], $data['comments']);
    }

    public static function getTasksByStatus(string $status, int $user_id, bool $is_admin = false) {
        $conn = DatabaseSingleton::getInstance();
        
        if ($is_admin) {
            $sql = "SELECT * FROM " . self::$table . " WHERE status = '$status'";
        } 
        else {
            $sql = "SELECT * FROM " . self::$table . " WHERE status RLIKE '$status\$' AND (assigned_to = $user_id OR created_by = $user_id)";
        }

        $query = $conn->query($sql);
        $raw_tasks = $query->fetch_all(MYSQLI_ASSOC);
        $tasks = [];
        
        foreach ($raw_tasks as $raw_task) {
            $tasks[] = Task::fromArray($raw_task);
        }
        return $tasks;
    }

    public static function delete($id) {
        $conn = DatabaseSingleton::getInstance();
        $sql = "DELETE FROM " . self::$table . " WHERE task_id = $id";
        return $conn->query($sql);
    }

    public static function resetWithUserDelete(int $user_id) {
        $conn = DatabaseSingleton::getInstance();

        $sql = "UPDATE " . self::$table . " SET created_by = NULL WHERE created_by = $user_id";
        $conn->query($sql);

        $sql = "UPDATE " . self::$table . " SET assigned_to = NULL WHERE assigned_to = $user_id";
        $conn->query($sql);
    }
    
}