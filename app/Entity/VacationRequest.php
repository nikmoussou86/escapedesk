<?php
declare(strict_types=1);

namespace App\Entity;

use App\Enums\RequestStatus;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

#[Entity]
#[Table('vacation_requests')]
class VacationRequest
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column]
    private string $reason;

    #[Column(name: 'date_from')]
    private \DateTime $dateFrom;

    #[Column(name: 'date_to')]
    private \DateTime $dateTo;

    #[Column(name: 'request_status')]
    private RequestStatus $requestStatus;

    #[Column(name: 'user_id')]
    private int $userId;

    #[Column(name: 'created_at')]
    private \DateTime $createdAt;

    #[Column(name: 'updated_at')]
    private \DateTime $updatedAt;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'vacationRequests')]
    #[JoinColumn(nullable: false, onDelete: "CASCADE")]
    private User $user;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

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

    
    public function getRequestStatus(): string
    {
        return ($this->requestStatus)->label();
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