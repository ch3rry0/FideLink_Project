<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'customers')]
class Customer
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $name = null;

    #[MongoDB\Field(type: 'string')]
    #[MongoDB\UniqueIndex]
    private ?string $fdl_id = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $email = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $pfp = null;

    #[MongoDB\Field(type: 'float')]
    private float $pointsBal = 0;

    #[MongoDB\Field(type: 'date')]
    private ?\DateTime $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    // Getters and Setters

    public function getId(): ?string
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

    public function getFdlId(): ?string
    {
        return $this->fdl_id;
    }

    public function setFdlId(string $fdl_id): self
    {
        $this->fdl_id = $fdl_id;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPfp(): ?string
    {
        return $this->pfp;
    }

    public function setPfp(?string $pfp): self
    {
        $this->pfp = $pfp;
        return $this;
    }

    public function getPointsBal(): float
    {
        return $this->pointsBal;
    }

    public function setPointsBal(float $pointsBal): self
    {
        $this->pointsBal = $pointsBal;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
