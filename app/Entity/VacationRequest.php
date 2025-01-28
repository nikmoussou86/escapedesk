<?php
declare(strict_types=1);

namespace App\Entity;

use App\Enums\RequestStatus;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
#[Table('vacation_requests')]
class VacationRequest
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column]
    private string $reason;

    #[Column('date_from')]
    private \DateTime $dateFrom;

    #[Column('date_to')]
    private \DateTime $dateTo;

    #[Column('request_status')]
    private RequestStatus $requestStatus;

    #[Column('user_id')]
    private int $userId;

    #[Column('created_at')]
    private \DateTime $createdAt;

    #[Column('updated_at')]
    private \DateTime $updatedAt;

    #[ManyToOne(inversedBy: 'vacationRequests')]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): VacationRequest
    {
        $this->reason = $reason;
        return $this;
    }

    
    public function getDateFrom(): \DateTime
    {
        return $this->dateFrom;
    }

    public function setDateFrom(\DateTime $dateFrom): VacationRequest
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    
    public function getDateTo(): \DateTime
    {
        return $this->dateTo;
    }

    public function setDateTo(\DateTime $dateTo): VacationRequest
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    
    public function getRequestStatus(): RequestStatus
    {
        return $this->requestStatus;
    }

    public function setRequestStatus(RequestStatus $requestStatus): VacationRequest
    {
        $this->requestStatus = $requestStatus;
        return $this;
    }

    
    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): VacationRequest
    {
        $this->userId = $userId;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): VacationRequest
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): VacationRequest
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}