<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Entity\User;
use App\Classes\AccessControl;
use App\Repositories\Contracts\SessionHandlerInterface;
use App\Repositories\Contracts\UserRepositoryInterface;


class BaseController {

    public function __construct(
        private SessionHandlerInterface $sessionHandler,
        protected UserRepositoryInterface $userRepository,
    )
    {
    }

    protected function hasAccess(string $permission): bool {
        $authUser = $this->getAuthUser();        
        
        if ($authUser) {
            // Check if user has persmissions
            if (!AccessControl::hasPermission($authUser, $permission)) {
                throw new \Exception('Access Denied');
            }
            // Grant access!
            return true;
        }
        return false; // User cant be found
    }

    private function getAuthUser(): ?User
    {        
        if (isset($_SESSION['user_id'])) {
            $authUser = $this->userRepository->findByUserId(intval($_SESSION['user_id']));

            if ($authUser) {
                return $authUser;
            }
            return null;
        }
        return null;
    }
}