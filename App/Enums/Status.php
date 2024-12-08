<?php

namespace App\Enums;

enum Status: string {
    case PENDING = 'Pending';
    case PROGRESS = 'In Progress';
    case COMPLETED = 'Completed';
}