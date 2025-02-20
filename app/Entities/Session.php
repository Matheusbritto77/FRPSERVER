<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="phpauth_sessions")
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $uid;

    /**
     * @ORM\Column(type="string", length=40, unique=true)
     */
    private string $hash;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $expiredate;

    // Getters and Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUid(): int
    {
        return $this->uid;
    }

    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function getExpiredate(): \DateTime
    {
        return $this->expiredate;
    }

    public function setExpiredate(\DateTime $expiredate): void
    {
        $this->expiredate = $expiredate;
    }
}
