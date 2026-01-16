<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'admins')]
class Admin
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $name = null;

    #[MongoDB\Field(type: 'string')]
    #[MongoDB\UniqueIndex]
    private ?string $email = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $password = null;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
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
