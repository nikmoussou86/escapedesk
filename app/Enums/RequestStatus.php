<?php
declare(strict_types=1);

namespace App\Enums;

enum RequestStatus: int
{
    case SUBMITTED = 0;
    case APPROVED = 1;
    case REJECTED = 2;
}