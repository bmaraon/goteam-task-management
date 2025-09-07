<?php

namespace App\Enums;

enum TaskStatuses: int
{
    case TO_DO = 0;
    case COMPLETED = 1;
}
