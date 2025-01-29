<?php
declare(strict_types=1);

namespace App\Entity;

use App\Enums\UserType;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[Entity]
#[Table('users')]
class User
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;
    
    #[Column(name: 'user_name')]
    private string $userName;
    
    #[Column]
    private string $email;
    
    #[Column]
    private string $password;

    #[Column(name: 'employee_code')]
    private string $employeeCode;
    
    #[Column]
    private UserType $type;

    #[Column(name: 'created_at')]
    private \DateTime $createdAt;

    #[Column(name: 'updated_at')]
    private \DateTime $updatedAt;

    #[OneToMany(targetEntity: VacationRequest::class, mappedBy: 'user')]
    private Collection $vacationRequests;


    public function __construct()
    {
        $this->vacationRequests = new ArrayCollection();
    }

    public function getVacationRequests(): Collection
    {
        return $this->vacationRequests;
    }

    public function addVacationRequest(VacationRequest $vacationRequest): self
    {
        if (!$this->vacationRequests->contains($vacationRequest)) {
            $this->vacationRequests[] = $vacationRequest;
            $vacationRequest->setUser($this);
        }

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->userName;
    }

    public function setUsername(string $userName): User
    {
        $this->userName = $userName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getEmployeeCode(): string
    {
        return $this->employeeCode;
    }

    public function setEmployeeCode(string $employeeCode): User
    {
        $this->employeeCode = $employeeCode;
        return $this;
    }

    public function getType(): string
    {
        return ($this->type)->label();
    }

    public function setType(UserType $type): User
    {
        $this->type = $type;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}