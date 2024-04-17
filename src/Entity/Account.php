<?php

namespace App\Entity;

use Doctrine\ORM\Mapping;

/**
 * Account
 */
#[Mapping\Table(name: "account")]
#[Mapping\Entity]
class Account
{
    /**
     * @var int
     */
    #[Mapping\Column(name: "id", type: "integer", nullable: false)]
    #[Mapping\Id]
    #[Mapping\GeneratedValue(strategy: "IDENTITY")]
    private int $id;

    /**
     * @var string
     */
    #[Mapping\Column(name: "name", type: "string", length: 255, nullable: false)]
    private string $name;

    /**
     * @var string
     */
    #[Mapping\Column(name: "prenom", type: "string", length: 255, nullable: false)]
    private string $prenom;

    /**
     * @var int
     */
    #[Mapping\Column(name: "age", type: "integer", nullable: false)]
    private int $age;

    /**
     * @var string
     */
    #[Mapping\Column(name: "mail", type: "string", length: 255, nullable: false)]
    private string $mail;

    /**
     * @var string
     */
    #[Mapping\Column(name: "motpasse", type: "string", length: 255, nullable: false)]
    private string $motpasse;

    /**
     * @var string
     */
    #[Mapping\Column(name: "title", type: "string", length: 50, nullable: false)]
    private string $title;

    /**
     * @var string|null
     */
    #[Mapping\Column(name: "phonenumber", type: "string", length: 20, nullable: true)]
    private ?string $phonenumber;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;
        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;
        return $this;
    }

    public function getMotpasse(): ?string
    {
        return $this->motpasse;
    }

    public function setMotpasse(string $motpasse): self
    {
        $this->motpasse = $motpasse;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;
        return $this;
    }

    public function __toString(): string
    {
        return $this->prenom . $this->name;
    }
}
