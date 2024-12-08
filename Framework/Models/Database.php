<?php

namespace Framework\Models;

final class Database{
    
    private string $host;
    private string $user;
    private string $password;
    private string $database;

    public \mysqli $conn;

    public function __construct(string $host, string $user, string $password, string $database){
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect() {
        $this->conn = new \mysqli($this->host, $this->user, $this->password, $this->database);
        return $this->conn;
    }
    
    public function close() {
        $this->conn->close();
    }
}
