<?php
declare(strict_types=1);

namespace App\Classes;

use App\Entity\User;
use App\Enums\UserType;

class AccessControl {

    private static $permissions = [
        'Manager' => [
            'user.view_all',
            'user.create',
            'user.edit',
            'user.update',
            'user.delete',
            'vacation.update',
        ],
        'Employee' => [
            'vacation.create',
            'vacation.view_own'
        ]
    ];

    public static function hasPermission(User $authUser, string $permission): bool {
        if (!isset(self::$permissions[$authUser->getTypeLabel()])) {
            return false;
        }
        
        return in_array($permission, self::$permissions[$authUser->getTypeLabel()]);
    }
}