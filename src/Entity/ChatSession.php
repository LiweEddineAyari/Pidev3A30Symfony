<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "chat_session")]
class ChatSession
{
    #[ORM\Id]
    #[ORM\Column(type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Account")]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id", nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Account")]
    #[ORM\JoinColumn(name: "id_user2", referencedColumnName: "id", nullable: false)]
    private $user2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Account
    {
        return $this->user;
    }

    public function setUser(Account $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getUser2(): ?Account
    {
        return $this->user2;
    }

    public function setUser2(Account $user2): self
    {
        $this->user2 = $user2;
        return $this;
    }
}
