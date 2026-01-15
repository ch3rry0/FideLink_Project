<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'merchants')]
class Merchant
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $name = null;

    #[MongoDB\Field(type: 'string')]
    #[MongoDB\UniqueIndex]
    private ?string $merchant_id = null;

    #[MongoDB\Field(type: 'string')]
    #[MongoDB\UniqueIndex]
    private ?string $email = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $password = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $pfp = null;

    #[MongoDB\EmbedOne(targetDocument: Location::class)]
    private ?Location $loc = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $bio = null;

    #[MongoDB\Field(type: 'float')]
    private ?float $pointVal = null;

    #[MongoDB\Field(type: 'float')]
    private ?float $miniThresh = null;

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

    public function getMerchantId(): ?string
    {
        return $this->merchant_id;
    }

    public function setMerchantId(string $merchant_id): self
    {
        $this->merchant_id = $merchant_id;
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

    public function getPfp(): ?string
    {
        return $this->pfp;
    }

    public function setPfp(?string $pfp): self
    {
        $this->pfp = $pfp;
        return $this;
    }

    public function getLoc(): ?Location
    {
        return $this->loc;
    }

    public function setLoc(?Location $loc): self
    {
        $this->loc = $loc;
        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;
        return $this;
    }

    public function getPointVal(): ?float
    {
        return $this->pointVal;
    }

    public function setPointVal(float $pointVal): self
    {
        $this->pointVal = $pointVal;
        return $this;
    }

    public function getMiniThresh(): ?float
    {
        return $this->miniThresh;
    }

    public function setMiniThresh(?float $miniThresh): self
    {
        $this->miniThresh = $miniThresh;
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
