<?php

namespace App\Model;

class Product implements ProductInterface
{
    protected $id;
    protected $name;
    protected $sku;
    protected $type = self::TYPE_SIMPLE;

    /**
     */
    public function __construct()
    {
        $this->type = self::TYPE_SIMPLE;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     */
    public function __clone()
    {
        if ($this->id) {
            $this->name = 'Copy of '.$this->name;
        }
    }
}
