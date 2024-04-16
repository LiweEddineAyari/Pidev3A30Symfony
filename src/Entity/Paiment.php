<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "paiment")]
class Paiment
{
    #[ORM\Id]
    #[ORM\Column(type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;
   
    #[ORM\ManyToOne(targetEntity: Account::class, cascade: ["persist"])]
    #[ORM\JoinColumn(name: "iduser", referencedColumnName: "id", nullable: false)]
    private $user;

    #[ORM\Column(type: "float", precision: 10, scale: 0, nullable: false)]
    private $montant;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private $cartname;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private $cartcode;

    #[ORM\Column(type: "date", nullable: false)]
    private $date;

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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    public function getCartname(): ?string
    {
        return $this->cartname;
    }

    public function setCartname(string $cartname): self
    {
        $this->cartname = $cartname;
        return $this;
    }

    public function getCartcode(): ?string
    {
        return $this->cartcode;
    }

    public function setCartcode(string $cartcode): self
    {
        $this->cartcode = $cartcode;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }
}
