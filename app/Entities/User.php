<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "users")]
#[ORM\HasLifecycleCallbacks]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $name;

    #[ORM\Column(type: "string", unique: true, length: 150)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $password;

    #[ORM\Column(type: "string", unique: true, nullable: true, length: 64)]
    private ?string $apiKey = null;

    #[ORM\Column(type: "datetime")]
    private \DateTime $createdAt;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }

    // Getters e Setters
    public function getId(): int { return $this->id; }
    
    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): void { 
        $this->password = password_hash($password, PASSWORD_BCRYPT); 
    }

    public function getApiKey(): ?string { return $this->apiKey; }
    public function setApiKey(?string $apiKey): void { $this->apiKey = $apiKey; }

    public function getCreatedAt(): \DateTime { return $this->createdAt; }

    public function getUpdatedAt(): ?\DateTime { return $this->updatedAt; }
}
