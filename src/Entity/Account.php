<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "account")]
#[ORM\Entity]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer")]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Name cannot be blank")]
    #[Assert\Type("string", message: "Name must be a string")]
    #[ORM\Column(name: "name", type: "string", length: 255)]
    private string $name;

    #[Assert\NotBlank(message: "Prenom cannot be blank")]
    #[Assert\Type("string", message: "Prenom must be a string")]
    #[ORM\Column(name: "prenom", type: "string", length: 255)]
    private string $prenom;

    #[Assert\NotBlank(message: "Age cannot be blank")]
    #[Assert\Type("int", message: "Age must be an integer")]
    #[Assert\Range(min: 1, max: 150, minMessage: "Age must be at least {{ limit }}", maxMessage: "Age cannot be greater than {{ limit }}")]
    #[ORM\Column(name: "age", type: "integer")]
    private int $age;

    #[Assert\NotBlank(message: "Mail cannot be blank")]
    #[Assert\Email(message: "Invalid email format")]
    #[ORM\Column(name: "mail", type: "string", length: 255)]
    private string $mail;

    #[Assert\NotBlank(message: "Motpasse cannot be blank")]
    #[Assert\Type("string", message: "Motpasse must be a string")]
    #[ORM\Column(name: "motpasse", type: "string", length: 255)]
    private string $motpasse;

    #[Assert\NotBlank(message: "Title cannot be blank")]
    #[Assert\Choice(choices: ['admin', 'coach', 'user'], message: "Invalid title. Title must be 'admin', 'coach', or 'user'")]
    #[Assert\Type("string", message: "Title must be a string")]
    #[Assert\Length(max: 50, maxMessage: "Title cannot be longer than {{ limit }} characters")]
    #[ORM\Column(name: "title", type: "string", length: 50)]
    private string $title;
    

    #[Assert\Regex("/^\d{8}$/", message: "Invalid phone number format. It must be 8 digits")]
#[ORM\Column(name: "phonenumber", type: "string", length: 20, nullable: true)]
private ?string $phonenumber = null;


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
        return $this->prenom . ' ' . $this->name;
    }
}
