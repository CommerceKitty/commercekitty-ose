<?php declare(strict_types=1);

namespace CommerceKitty\Model;

/**
 */
class Inventory implements InventoryInterface
{
    protected $id;
    protected $warehouse;
    protected $product;
    protected $quantity;

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
    public function getWarehouse(): ?WarehouseInterface
    {
        return $this->warehouse;
    }

    /**
     */
    public function setWarehouse(WarehouseInterface $warehouse): self
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    /**
     */
    public function setProduct(ProductInterface $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
