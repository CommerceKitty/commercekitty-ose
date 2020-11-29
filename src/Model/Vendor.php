<?php declare(strict_types=1);

namespace App\Model;

/**
 */
class Vendor implements VendorInterface
{
    protected $id;
    protected $name;

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
}
