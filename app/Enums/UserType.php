<?php
declare(strict_types=1);

namespace App\Enums;

enum UserType: int
{
    case MANAGER = 1;
    case EMPLOYEE = 2;

    public function label(): string
    {
        return match ($this) {
            self::MANAGER => 'Manager',
            self::EMPLOYEE => 'Employee',
        };
    }

    public static function fromValueToString(int $value): string
    {
        return match ($value) {
            self::MANAGER->value => 'Manager',
            self::EMPLOYEE->value => 'Employee',
            default => throw new \InvalidArgumentException('Invalid UserType value'),
        };
    }
}