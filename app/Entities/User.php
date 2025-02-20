<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="phpauth_users")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\Column(type="boolean", options={"default"=true})
     */
    private bool $isactive = true;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $token = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $dt;

    // Getters and Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isActive(): bool
    {
        return $this->isactive;
    }

    public function setIsActive(bool $isactive): void
    {
        $this->isactive = $isactive;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getDt(): \DateTime
    {
        return $this->dt;
    }

    public function setDt(\DateTime $dt): void
    {
        $this->dt = $dt;
    }
}
