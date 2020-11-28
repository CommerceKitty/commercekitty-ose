<?php declare(strict_types=1);

namespace App\Model;

/**
 */
class Warehouse implements WarehouseInterface
{
    protected $id;
    protected $name;
    protected $address;

    /**
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
    public function getAddress(): ?AddressInterface
    {
        return $this->address;
    }

    /**
     */
    public function setAddress(AddressInterface $address): self
    {
        $this->address = $address;

        return $this;
    }
}
