<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "gym")]
class Gym
{
    #[ORM\Id]
    #[ORM\Column(type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    #[ORM\Column(type: "integer", nullable: false, name: "id_user")]
    private $idUser;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private $nom;

    #[ORM\Column(type: "integer", nullable: false, name: "client_number")]
    private $clientNumber;

    #[ORM\Column(type: "integer", nullable: false, name: "coach_number")]
    private $coachNumber;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getClientNumber(): ?int
    {
        return $this->clientNumber;
    }

    public function setClientNumber(int $clientNumber): self
    {
        $this->clientNumber = $clientNumber;
        return $this;
    }

    public function getCoachNumber(): ?int
    {
        return $this->coachNumber;
    }

    public function setCoachNumber(int $coachNumber): self
    {
        $this->coachNumber = $coachNumber;
        return $this;
    }
}
