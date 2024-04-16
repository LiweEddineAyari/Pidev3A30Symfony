<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "commande")]
class Commande
{
    #[ORM\Id]
    #[ORM\Column(type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Account", cascade: ["persist"])]
    #[ORM\JoinColumn(name: "iduser", referencedColumnName: "id", nullable: false)]
    private $user;
    

    #[ORM\ManyToOne(targetEntity: "App\Entity\Panier")]
    #[ORM\JoinColumn(name: "idpanier", referencedColumnName: "id", nullable: false)]
    private $panier;

    #[ORM\Column(type: "float", precision: 10, scale: 0, nullable: false)]
    private $montant;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private $statut;

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

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): self
    {
        $this->panier = $panier;
        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }
}
