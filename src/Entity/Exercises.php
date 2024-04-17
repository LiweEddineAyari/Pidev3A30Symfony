<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: "exercises")]
class Exercises
{
    #[ORM\Id]
    #[ORM\Column(type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Product", inversedBy: "exercises")]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "id", nullable: true)]
    private $product;

    // ...

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Name cannot be blank")]
    #[Assert\Length(max: 20, maxMessage: "Description cannot be longer than {{ limit }} characters.")]
    private $name;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private $target;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private $type;

    #[ORM\Column(type: "text", length: 65535, nullable: true)]
    #[Assert\NotBlank(message: "Description cannot be blank")]
    #[Assert\Length(min: 8, maxMessage: "Description cannot be longer than {{ limit }} characters.")]
    private $description;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $img;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private $intensity;

    #[ORM\Column(type: "string", length: 3, nullable: true, name: "EquipmentNeeded")]
    private $equipmentneeded;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(?string $target): static
    {
        $this->target = $target;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;
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

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): static
    {
        $this->img = $img;
        return $this;
    }

    public function getIntensity(): ?string
    {
        return $this->intensity;
    }

    public function setIntensity(?string $intensity): static
    {
        $this->intensity = $intensity;
        return $this;
    }

    public function getEquipmentneeded(): ?string
    {
        return $this->equipmentneeded;
    }

    public function setEquipmentneeded(?string $equipmentneeded): static
    {
        $this->equipmentneeded = $equipmentneeded;
        return $this;
    }
}
