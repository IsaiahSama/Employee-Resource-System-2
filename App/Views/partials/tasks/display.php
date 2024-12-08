<?php 

use App\Enums\Status;
use App\Models\UserRepository;
use Framework\Views\Layout;

if (!isset($tasks) || empty($tasks)) {
    header('HTTP/1.0 404 Not Found');
    Layout::contentRender('errors/404', ['error' => 'No tasks found matching that criteria.']);
    die();
}

echo "<small> Click a task to edit it </small>";
foreach ($tasks as $task) {

    $background_color = match($task->status) {
        Status::PENDING => 'has-background-warning-dark',
        Status::PROGRESS => 'has-background-info-dark',
        Status::COMPLETED => 'has-background-success-dark',
        default => 'has-background-danger-dark'
    };

    $text_color = match($task->status) {
        Status::PENDING => 'has-text-warning',
        Status::PROGRESS => 'has-text-info',
        Status::COMPLETED => 'has-text-success',
        default => 'has-text-danger'
    };

    echo "<a href='/task/edit?id=$task->id'>
    <div class='box is-fullwidth my-3 $background_color $text_color'>
        <h3 class='title $text_color' > $task->title</h3>
        <p> $task->description</p>
        <div class='is-flex is-flex-wrap-wrap is-justify-content-space-between my-2'>
            <p> Status: " . $task->status->value . " </p>
            <p> Due Date: $task->due_date</p>
        </div>
        <div class='is-flex is-flex-wrap-wrap is-justify-content-space-between my-2'>
            <p> Assigned To: " . UserRepository::find($task->assigned_to)->username . " </p>
            <p> Assigned By: " . UserRepository::find($task->created_by)->username . "</p>
        </div>
        <div class='content'>
            <p> Comments: " . (empty($task->comments) ? 'None' : $task->comments) . "</p>
        </div>
    </div>
    </a>";
}