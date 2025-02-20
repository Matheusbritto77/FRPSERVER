<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "orders")]
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: "string", length: 255, unique: true)]  // Campo imei definido como string (texto)
    private string $imei;

    #[ORM\Column(type: "integer", options: ['default' => 1])]  // Campo status definido como inteiro com valor padrão 1
    private int $status = 1;  // Define o valor padrão para o status como 1

    // Getters e Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getImei(): string
    {
        return $this->imei;
    }

    public function setImei(string $imei): void
    {
        $this->imei = $imei;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        if (!in_array($status, [1, 3, 4])) {
            throw new \InvalidArgumentException('Status must be 1, 3, or 4');
        }
        $this->status = $status;
    }
}
