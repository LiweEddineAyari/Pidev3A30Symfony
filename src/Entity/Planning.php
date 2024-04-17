<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "planning")]
#[ORM\Entity]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", nullable: false)]
    private $id;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Account")]
    #[ORM\JoinColumn(name: "id_coach", referencedColumnName: "id", nullable: false)]
    private $coach;

    #[ORM\Column(name: "date_debut", type: "string", length: 10, nullable: false)]
    #[Assert\NotBlank(message: "The start date cannot be empty.")]
    private $dateDebut;

    #[ORM\Column(name: "date_fin", type: "string", length: 10, nullable: false)]
    #[Assert\NotBlank(message: "The end date cannot be empty.")]
    #[Assert\GreaterThan(propertyPath: "dateDebut", message: "The end date should be greater than the start date.")]
    private $dateFin;

    #[ORM\Column(name: "heure_debut", type: "time", nullable: false)]
    #[Assert\NotBlank(message: "The start time cannot be empty.")]
    private $heureDebut;

    #[ORM\Column(name: "heure_fin", type: "time", nullable: false)]
    #[Assert\NotBlank(message: "The end time cannot be empty.")]
    #[Assert\GreaterThan(propertyPath: "heureDebut", message: "The end time should be greater than the start time.")]
    private $heureFin;

    #[ORM\Column(name: "titre", type: "string", length: 255, nullable: false)]
    private $titre;

    #[ORM\Column(name: "description", type: "text", length: 65535, nullable: true)]
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoach(): ?Account
    {
        return $this->coach;
    }

    public function setCoach(?Account $coach): self
    {
        $this->coach = $coach;
        return $this;
    }

    public function getDateDebut(): ?string
    {
        return $this->dateDebut;
    }

    public function setDateDebut(string $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }

    public function setDateFin(string $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(\DateTimeInterface $heureDebut): static
    {
        $this->heureDebut = $heureDebut;
        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heureFin;
    }

    public function setHeureFin(\DateTimeInterface $heureFin): static
    {
        $this->heureFin = $heureFin;
        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }
}
