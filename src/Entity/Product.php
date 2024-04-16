<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "product")]
#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "name", type: "string", length: 50, nullable: false)]
    #[Assert\NotBlank]
    private $name;

    #[ORM\Column(name: "ref", type: "string", length: 50, nullable: false)]
    #[Assert\NotBlank]
    private $ref;

    #[ORM\Column(name: "price", type: "float", precision: 10, scale: 0, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(type: "float", message: "The value {{ value }} is not a valid {{ type }}.")]
    private $price;

    #[ORM\Column(name: "quantity", type: "integer", nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(type: "integer", message: "The value {{ value }} is not a valid {{ type }}.")]
    private $quantity;

    #[ORM\Column(name: "weight", type: "integer", nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(type: "integer", message: "The value {{ value }} is not a valid {{ type }}.")]
    private $weight;

    #[ORM\Column(name: "imageUrl", type: "string", length: 150, nullable: false)]
    private $imageUrl;

    #[ORM\Column(name: "description", type: "text", length: 65535, nullable: false)]
    #[Assert\NotBlank]
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;
        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function __toString()
    {
        return $this->name; // Return the name property as the string representation
    }
}
