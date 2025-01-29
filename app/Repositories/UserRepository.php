<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Entity\User;
use App\Enums\UserType;
use Doctrine\ORM\EntityManager;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface {
    
    public function __construct(
        private EntityManager $entityManager
    ) 
    {    
    }

    public function findAll()
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'userName' => $user->getUsername(),
                'email' => $user->getEmail(),
                'type' => $user->getType(),
                'employeeCode' => $user->getEmployeeCode(),
                'createdAt' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }
        
        return $data;
    }

    public function create(array $data): void {
        $user = new User();
        $user->setUsername($data['user_name']);
        $user->setEmail($data['email']);
        $user->setEmployeeCode($data['employee_code']);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        $user->setType(UserType::tryFrom(intval($data['type'])));
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function update(array $data): void {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        $user->setUsername($data['user_name']);
        $user->setEmail($data['email']);
        $user->setEmployeeCode($data['employee_code']);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        $user->setType(UserType::tryFrom(value: intval($data['type'])));        
        $user->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function delete(User $user): void {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function findByCredentials(string $email, string $password): ?User {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user) {
            return $user;
        }

        return null;
    }

    public function findByUserId(int $userId): ?User
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);

        if ($user) {
            return $user;
        }

        return null;
    }
}
