<?php

namespace App\Models;

use Framework\Models\DatabaseSingleton;
use Framework\Models\Repository;

class UserRepository extends Repository {
    public static string $table = 'Users';
    
    /**
     * Inserts a new user record into the database.
     *
     * @param array $data An associative array containing the keys 'username', 'email', 'password', and 'auth_level'.
     * 
     * @return bool Returns true on success or false on failure.
     */
    public static function create($data) {
        $conn = DatabaseSingleton::getInstance();

        $sql = "INSERT INTO " . self::$table . " (username, email, password, auth_level) VALUES (?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $data['username'], $data['email'], $data['password'], $data['auth_level']);
        return $stmt->execute();
    }

    public static function findById($key) {
        $conn = DatabaseSingleton::getInstance();

        $sql = "SELECT * FROM " . self::$table . " WHERE user_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $key);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data) {
            return User::fromArray($data);
        }

        return null;
    }
    
    public static function find($key) {
        return self::findById($key);
    }
    
    public static function getAll() {
        $conn = DatabaseSingleton::getInstance();

        $sql = "SELECT * FROM " . self::$table;

        $query = $conn->query($sql);

        $users = [];

        while ($row = $query->fetch_assoc()) {
            $users[] = User::fromArray($row);
        }

        return $users;
    }
    
    public static function findWhere($key, $value) {
        $conn = DatabaseSingleton::getInstance();

        if ($key == "id") {
            return self::findById($value);
        }

        $sql = "SELECT * FROM " . self::$table . " WHERE $key = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data) {
            return User::fromArray($data);
        }

        return null;
    }

    public static function findWhereNot($key, $value) {
        $conn = DatabaseSingleton::getInstance();

        if ($key == "id") {
            return self::findById($value);
        }

        $sql = "SELECT * FROM " . self::$table . " WHERE $key != ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        
        while ($row = $result->fetch_assoc()) {
            $users[] = User::fromArray($row);
        }

        return $users;
    }
    
    public static function update($id, $key, $value) {
        $conn = DatabaseSingleton::getInstance();

        $sql = "UPDATE " . self::$table . " SET $key = ? WHERE user_id = $id";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $value);
        return $stmt->execute();
    }
    

    public static function delete($id) {
        // Update Tasks table as well
        TaskRepository::resetWithUserDelete($id);
        
        $conn = DatabaseSingleton::getInstance();

        $sql = "DELETE FROM " . self::$table . " WHERE user_id = $id";

        return $conn->query($sql);
    }
    
}