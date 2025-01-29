<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Entity\User;
use Twig\Environment;
use Psr\Http\Message\ServerRequestInterface;
use App\Repositories\Contracts\PasswordHasherInterface;
use App\Repositories\Contracts\SessionHandlerInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class AuthController extends BaseController
{
    public function __construct(
        protected SessionHandlerInterface $sessionHandler,
        protected UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
        private Environment $twig,
    )
    {
    }

    public function index(): void
    {
        // If user is not logged in go to login page
        if (!$this->sessionHandler->userIsLoggedIn()) {
            header('Location: /login');
            exit;
        }

        // Get auth user
        $authUser = $this->getAuthUser();
        if ($authUser) {
            // Redirect baesed on role
            $this->roleBasedRedirect($authUser);
        }
    }

    public function showLoginForm(): string
    {
        if ($this->sessionHandler->userIsLoggedIn()) {
            header('Location: /users');
            exit;
        }

        // Render the login form
        return $this->twig->render('auth/login.twig');
    }


    public function login(ServerRequestInterface $request): void
    {
        $data = $request->getParsedBody();

        // Find the user by email
        $user = $this->userRepository->findByCredentials($data['email'], $data['password']);

        if (!$user) {
            throw new \Exception('User not found!');
        }
        
        // Verify the password
        if (!$this->passwordHasher->verify($data['password'], $user->getPassword())) {
            throw new \Exception('Invalid pasword!');
        }

        // Start the session and store user data
        $this->sessionHandler->set( 'user_id', intval($user->getId()));

        $this->roleBasedRedirect($user);
    }

    private function roleBasedRedirect(User $user)
    {
        try {
            // If user is logged in and manager go to 'users' list page
            if ($user->userIsManager()) {
                header('Location: /users');
                exit;
            }
    
            // If user is logged in and employee go to 'vac requests' list page
            if ($user->userIsEmployee()) {
                header('Location: /vacation_requests');
                exit;
            }
        } catch (\Exception $e) {
            echo "User doesnt have any available role! {$e->getMessage()}";
            die;
        }
    }

    public function logout(): string
    {
        $this->sessionHandler->destroy();
        header('Location: /login');
        exit;
    }
}
