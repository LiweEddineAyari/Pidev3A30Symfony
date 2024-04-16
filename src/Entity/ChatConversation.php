<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "chat_conversation")]
class ChatConversation
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    #[ORM\ManyToOne(targetEntity: "App\Entity\ChatSession")]
    #[ORM\JoinColumn(name: "id_chatSession", referencedColumnName: "id", nullable: true)]
    private $chatSession;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Account")]
    #[ORM\JoinColumn(name: "id_sender", referencedColumnName: "id", nullable: true)]
    private $sender;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Account")]
    #[ORM\JoinColumn(name: "id_reciever", referencedColumnName: "id", nullable: true)]
    private $receiver;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Planning")]
    #[ORM\JoinColumn(name: "id_planning", referencedColumnName: "id", nullable: true)]
    private $planning;

    #[ORM\Column(name: "message", type: "string", length: 255, nullable: false)]
    private $message;

    #[ORM\Column(name: "imageUrl", type: "string", length: 255, nullable: true)]
    private $imageUrl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChatSession(): ?ChatSession
    {
        return $this->chatSession;
    }

    public function setChatSession(?ChatSession $chatSession): self
    {
        $this->chatSession = $chatSession;
        return $this;
    }

    public function getSender(): ?Account
    {
        return $this->sender;
    }

    public function setSender(?Account $sender): self
    {
        $this->sender = $sender;
        return $this;
    }

    public function getReceiver(): ?Account
    {
        return $this->receiver;
    }

    public function setReceiver(?Account $receiver): self
    {
        $this->receiver = $receiver;
        return $this;
    }

    public function getPlanning(): ?Planning
    {
        return $this->planning;
    }

    public function setPlanning(?Planning $planning): self
    {
        $this->planning = $planning;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }
}
