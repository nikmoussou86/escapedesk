<?php
declare(strict_types=1);

namespace App\Controllers;


use Twig\Environment;
use App\Repositories\UserRepository;
use Psr\Http\Message\ServerRequestInterface;


class UserController
{
    public function __construct(
        private UserRepository $userRepository,
        private Environment $twig,
    )
    {}

    public function index()
    {
        // $users = [
        //     [
        //         'id' => 1,
        //         'name' => 'John Doe',
        //         'email' => 'john.doe@example.com',
        //         'employee_code' => 'EMP001',
        //     ],
        //     [
        //         'id' => 2,
        //         'name' => 'Jane Smith',
        //         'email' => 'jane.smith@example.com',
        //         'employee_code' => 'EMP002',
        //     ],
        //     [
        //         'id' => 3,
        //         'name' => 'Alice Johnson',
        //         'email' => 'alice.johnson@example.com',
        //         'employee_code' => 'EMP003',
        //     ],
        // ];

        $users = $this->userRepository->getAll();

        return $this->twig->render('users/index.twig', [
            'users' => $users
        ]);
    }

    public function create(): string
    {
        // Todo: Replace with a proper templating engine.
        return $this->twig->render('users/create.twig');
        // return <<<HTML
        //     <form action="/users/store" method="POST">
        //         <label for="user_name">Username:</label>
        //         <input type="text" name="user_name" id="user_name" required>
        //         <label for="email">Email:</label>
        //         <input type="email" name="email" id="email" required>
        //         <label for="password">Password:</label>
        //         <input type="password" name="password" id="password" required>
        //         <label for="type">Type:</label>
        //         <input type="number" name="type" id="type" required>
        //         <button type="submit">Create</button>
        //     </form>
        // HTML;
    }

    public function store(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();

        // Store the user in the database
        $this->userRepository->create($data);
    }

    public function edit(ServerRequestInterface $request, array $params)
    {
        $user = $this->userRepository->findByUserId(intval($params['userId']));

        if ($user) {
            return $this->twig->render('users/edit.twig', [
                'user' => $user
            ]);
        }
    }


    public function update(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();

        // Update the user in the database
        $this->userRepository->update($data);
    }

    public function delete(ServerRequestInterface $request, array $params)
    {
        $user = $this->userRepository->findByUserId(intval($params['userId']));

        if ($user) {
            $this->userRepository->delete($user);
        }
        
        return false;
    }
}