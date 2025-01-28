<?php
declare(strict_types=1);

namespace App\Enums;

enum UserType: int
{
    case MANAGER = 1;
    case EMPLOYEE = 2;
}