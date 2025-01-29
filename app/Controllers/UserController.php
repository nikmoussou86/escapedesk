<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Entity\User;
use Twig\Environment;
use Psr\Http\Message\ServerRequestInterface;
use App\Repositories\Contracts\SessionHandlerInterface;
use App\Repositories\Contracts\UserRepositoryInterface;


class UserController extends BaseController
{
    public function __construct(
        protected SessionHandlerInterface $sessionHandler,
        protected UserRepositoryInterface $userRepository,
        protected Environment $twig,
    )
    {
    }

    public function index()
    {
        $this->hasAccess('user.view_all');
        $users = $this->userRepository->findAll();

        return $this->twig->render('users/index.twig', [
            'authUser' => $this->getAuthUser(),
            'users' => $users,
        ]);
    }

    public function create(): string
    {
        $this->hasAccess('user.create');

        return $this->twig->render('users/create.twig', [
            'authUser' => $this->getAuthUser(),
        ]);
    }

    public function store(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();

        if (!preg_match('/^\d{7}$/', $data['employee_code'])) {
            throw new \InvalidArgumentException("Code must be exactly 7 digits.");

            // Todo: return some error message to the user
        }

        // Store the user in the database
        $this->userRepository->create($data);

        header('Location: /users');
        exit;
    }

    public function edit(ServerRequestInterface $request, array $params)
    {
        $this->hasAccess('user.edit');

        $user = $this->userRepository->findByUserId(intval($params['userId']));

        if ($user) {
            return $this->twig->render('users/edit.twig', [
                'user' => $user,
                'authUser' => $this->getAuthUser(),
            ]);
        }
    }


    public function update(ServerRequestInterface $request)
    {
        $this->hasAccess('user.update');

        $data = $request->getParsedBody();

        // Update the user in the database
        $this->userRepository->update($data);

        header('Location: /users');
        exit;
    }

    public function delete(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $user = $this->userRepository->findByUserId(intval($data['id']));

        if ($user) {
            $this->userRepository->delete($user);
            
            header('Location: /users');
            exit;
        }
        return false;
    }

    protected function getAuthUser(): ?User
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