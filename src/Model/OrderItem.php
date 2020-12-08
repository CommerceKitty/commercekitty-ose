<?php declare(strict_types=1);

namespace CommerceKitty\Model;

/**
 */
class OrderItem implements OrderItemInterface
{
    protected $id;
    protected $fid;
    protected $order;
    protected $product;
    protected $quantity;

    /**
     * {@inheritdoc}
     */
    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    /**
     */
    public function setOrder(OrderInterface $order): self
    {
        $this->order = $order;

        return $this;
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
    public function getFid(): ?string
    {
        return $this->fid;
    }

    /**
     */
    public function setFid(string $fid): self
    {
        $this->fid = $fid;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct(): ProductInterface
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
    public function getQuantity(): int
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
