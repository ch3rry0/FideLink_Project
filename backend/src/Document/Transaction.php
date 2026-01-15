<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'transactions')]
class Transaction
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: 'string')]
    #[MongoDB\Index]
    private ?string $customer_id = null;

    #[MongoDB\Field(type: 'string')]
    #[MongoDB\Index]
    private ?string $merchant_id = null;

    #[MongoDB\Field(type: 'int')]
    private ?int $type = null;

    #[MongoDB\Field(type: 'float')]
    private ?float $amount = null;

    #[MongoDB\Field(type: 'float')]
    private ?float $pts = null;

    #[MongoDB\Field(type: 'float')]
    private float $tx_pts = 0.01; // Constante 1pts = 0.01€

    #[MongoDB\Field(type: 'string')]
    private ?string $note = null;

    #[MongoDB\Field(type: 'date')]
    private ?\DateTime $transacDate = null;

    public function __construct()
    {
        $this->transacDate = new \DateTime();
    }

    // Getters and Setters

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCustomerId(): ?string
    {
        return $this->customer_id;
    }

    public function setCustomerId(string $customer_id): self
    {
        $this->customer_id = $customer_id;
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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getPts(): ?float
    {
        return $this->pts;
    }

    public function setPts(float $pts): self
    {
        $this->pts = $pts;
        return $this;
    }

    public function getTxPts(): float
    {
        return $this->tx_pts;
    }

    public function setTxPts(float $tx_pts): self
    {
        $this->tx_pts = $tx_pts;
        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function getTransacDate(): ?\DateTime
    {
        return $this->transacDate;
    }

    public function setTransacDate(\DateTime $transacDate): self
    {
        $this->transacDate = $transacDate;
        return $this;
    }
}
