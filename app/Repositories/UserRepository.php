<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Entity\User;
use App\Enums\UserType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface {
    
    public function __construct(
        private EntityManager $entityManager
    ) 
    {}

    public function findAll()
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'userName' => $user->getUsername(),
                'email' => $user->getEmail(),
                'type' => $user->getTypeLabel(),
                'employeeCode' => $user->getEmployeeCode(),
                'createdAt' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }
        
        return $data;
    }

    public function create(array $data): void {
        try {
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
        } catch (ORMException | \Exception $e) {
            throw new \Exception('Failed to create user: ' . $e->getMessage());
        }
    }

    public function update(array $data): void {
        try {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => intval($data['id'])]);
            
            if (!$user) {
                throw new \Exception('User not found');
            }
            
            $user->setUsername($data['user_name']);
            $user->setEmail($data['email']);
            $user->setEmployeeCode($data['employee_code']);
            $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
            $user->setType(UserType::tryFrom(intval($data['type'])));
            $user->setUpdatedAt(new \DateTime());

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (ORMException | \Exception $e) {
            throw new \Exception('Failed to update user: ' . $e->getMessage());
        }
    }

    public function delete(User $user): void {
        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        } catch (ORMException | \Exception $e) {
            throw new \Exception('Failed to delete user: ' . $e->getMessage());
        }
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
