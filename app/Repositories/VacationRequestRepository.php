<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Entity\User;
use App\Enums\RequestStatus;
use App\Entity\VacationRequest;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use App\Repositories\Contracts\VacationRequestRepositoryInterface;

class VacationRequestRepository implements VacationRequestRepositoryInterface {
    
    public function __construct(
        private EntityManager $entityManager
    ) 
    {    
    }

    public function getThem(User $authUser)
    {
        if ($authUser->userIsManager()) {
            $vacationRequests = $this->entityManager
                ->getRepository(VacationRequest::class)
                ->findAll();
        } else {
            $vacationRequests = $this->entityManager
                ->getRepository(VacationRequest::class)
                ->findBy(['userId' => $authUser->getId()]); 
        }


        $data = [];
        foreach ($vacationRequests as $vacationRequest) {
            $data[] = [
                'id' => $vacationRequest->getId(),
                'reason' => $vacationRequest->getReason(),
                'dateFrom' => $vacationRequest->getDateFrom()->format('Y-m-d'),
                'dateTo' => $vacationRequest->getDateTo()->format('Y-m-d'),
                'status' => $vacationRequest->getRequestStatus(),
                'createdAt' => $vacationRequest->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $vacationRequest->getUpdatedAt()->format('Y-m-d H:i:s'),
                'userName' => $vacationRequest->getUser()->getUsername(),
            ];
        }

        return $data;
    }

    public function create(array $data): void {
        try {
            $vacationRequest = new VacationRequest();
            $vacationRequest->setReason($data['reason']);
            $vacationRequest->setDateFrom(\DateTime::createFromFormat('Y-m-d', $data['date_from']));
            $vacationRequest->setDateTo(\DateTime::createFromFormat('Y-m-d', $data['date_to']));
            $vacationRequest->setRequestStatus(RequestStatus::PENDING);
            $vacationRequest->setUserId(intval($data['user_id']));
            $vacationRequest->setCreatedAt(new \DateTime());
            $vacationRequest->setUpdatedAt(new \DateTime());

            $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => intval($data['user_id'])]);
            if (!$user) {
                throw new \Exception('User not found');
            }
            $vacationRequest->setUser($user);

            $this->entityManager->persist($vacationRequest);
            $this->entityManager->flush();
        } catch (ORMException | \Exception $e) {
            throw new \Exception('Failed to create vacation request: ' . $e->getMessage());
        }
    }

    public function update(array $data): void {
        try {
            $vacationRequest = $this->entityManager->getRepository(VacationRequest::class)->findOneBy(['id' => $data['id']]);
            if (!$vacationRequest) {
                throw new \Exception('Vacation request not found');
            }
            $vacationRequest->setRequestStatus(RequestStatus::tryFrom(intval($data['status'])));
            $vacationRequest->setUpdatedAt(new \DateTime());

            $this->entityManager->persist($vacationRequest);
            $this->entityManager->flush();
        } catch (ORMException | \Exception $e) {
            throw new \Exception('Failed to update vacation request: ' . $e->getMessage());
        }
    }

    public function delete(VacationRequest $vacationRequest): void {
        try {
            $this->entityManager->remove($vacationRequest);
            $this->entityManager->flush();
        } catch (ORMException | \Exception $e) {
            throw new \Exception('Failed to delete vacation request: ' . $e->getMessage());
        }
    }

    public function findByVacationRequestId(int $id): ?array {
        $vacationRequest = $this->entityManager->getRepository(VacationRequest::class)->findOneBy(['id' => $id]);

        if ($vacationRequest) {
            return [
                'id' => $vacationRequest->getId(),
                'reason' => $vacationRequest->getReason(),
                'dateFrom' => $vacationRequest->getDateFrom()->format('Y-m-d'),
                'dateTo' => $vacationRequest->getDateTo()->format('Y-m-d'),
                'status' => $vacationRequest->getRequestStatus(),
                'createdAt' => $vacationRequest->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $vacationRequest->getUpdatedAt()->format('Y-m-d H:i:s'),
                'userName' => $vacationRequest->getUser()->getUsername(),
            ];
        }

        return null;
    }

    public function findVacRequestById(int $vacationRequestId): ?VacationRequest
    {
        $vacationRequest = $this->entityManager->getRepository(VacationRequest::class)->findOneBy(['id' => $vacationRequestId]);
        if ($vacationRequest) {
            return $vacationRequest;
        }
        return null;
    }
}
