<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "blockedusers")]
class Blockedusers
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: "Account")]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id", nullable: false)]
    private Account $user;

    #[ORM\ManyToOne(targetEntity: "Account")]
    #[ORM\JoinColumn(name: "id_user_blocked", referencedColumnName: "id", nullable: false)]
    private Account $blockedUser;

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

    public function getBlockedUser(): ?Account
    {
        return $this->blockedUser;
    }

    public function setBlockedUser(Account $blockedUser): self
    {
        $this->blockedUser = $blockedUser;
        return $this;
    }
}
