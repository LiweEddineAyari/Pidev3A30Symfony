<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "panier")]
#[ORM\Entity]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", nullable: false)]
    private $id;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Account")]
    #[ORM\JoinColumn(name: "iduser", referencedColumnName: "id", nullable: false)]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Account
    {
        return $this->user;
    }

    public function setUser(?Account $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function __toString(): string
    {
        return "num " . $this->id; // Return the name property as the string representation
    }
}
