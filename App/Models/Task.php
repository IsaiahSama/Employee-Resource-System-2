<?php

namespace App\Models;

use App\Enums\Status;

use Framework\Models\Model;

class Task extends Model {
    public int $id;
    public string $title;
    public string $description;
    public Status $status;
    public int $assigned_to;
    public int $created_by;
    public string $due_date;
    public string $comments;

    public function __construct($task_id, $title, $description, $status, $assigned_to, $created_by, $due_date, $comments) {
        $this->id = $task_id;
        $this->title = $title;
        $this->description = $description;
        $this->status = match($status) { 
            Status::PENDING->value => Status::PENDING,
            Status::PROGRESS->value => Status::PROGRESS,
            Status::COMPLETED->value => Status::COMPLETED,
            default => Status::PENDING
        };
        $this->assigned_to = $assigned_to;
        $this->created_by = $created_by;
        $this->due_date = $due_date;
        $this->comments = $comments;
    }

    public static function fromArray(array $data) {
        return new Task(
            $data['task_id'],
            $data['title'],
            $data['description'],
            $data['status'],
            $data['assigned_to'],
            $data['created_by'],
            $data['due_date'],
            $data['comments']
        );
    }
}