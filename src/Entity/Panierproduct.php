<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "panierproduct")]
#[ORM\Entity]
class Panierproduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", nullable: false)]
    private $id;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Panier")]
    #[ORM\JoinColumn(name: "idpanier", referencedColumnName: "id", nullable: false)]
    private $panier;
    #[ORM\ManyToOne(targetEntity: "App\Entity\Product", cascade: ["persist"])]
    #[ORM\JoinColumn(name: "idproduct", referencedColumnName: "id", nullable: false)]
    private $product;
    
    
    

    #[ORM\Column(name: "quantite", type: "integer", nullable: false)]
    private $quantite;

    #[ORM\Column(name: "prix", type: "float", precision: 10, scale: 0, nullable: false)]
    private $prix;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }
}
