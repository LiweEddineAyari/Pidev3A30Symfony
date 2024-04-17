<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: "paiment")]
class Paiment
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;
   
    #[ORM\ManyToOne(targetEntity: Account::class, cascade: ["persist"])]
    #[ORM\JoinColumn(name: "iduser", referencedColumnName: "id")]
    private $user;

    #[ORM\Column(nullable: true)]
    #[Assert\Type(type :"float", message:"Montant must be a valid number.")]
    #[Assert\GreaterThan(value:0, message:"Montant must be greater than 0.")]
    private $montant;

    #[ORM\Column(nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Cartcode cannot be longer than {{ limit }} characters.")]
    
    private $cartname;

    #[ORM\Column]
    #[Assert\Length(max: 255, maxMessage: "Cartcode cannot be longer than {{ limit }} characters.")]
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
