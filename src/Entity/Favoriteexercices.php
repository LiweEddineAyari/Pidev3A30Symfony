<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "favoriteexercices")]
class Favoriteexercices
{
    #[ORM\Id]
    #[ORM\Column(type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    #[ORM\Column(type: "integer", nullable: false, name: "iduser")]
    private $iduser;

    #[ORM\Column(type: "integer", nullable: false, name: "idexercice")]
    private $idexercice;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(int $iduser): self
    {
        $this->iduser = $iduser;
        return $this;
    }

    public function getIdexercice(): ?int
    {
        return $this->idexercice;
    }

    public function setIdexercice(int $idexercice): self
    {
        $this->idexercice = $idexercice;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }
}
