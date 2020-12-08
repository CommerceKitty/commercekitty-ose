<?php declare(strict_types=1);

namespace CommerceKitty\Model;

/**
 */
interface OrderItemInterface
{
    /**
     * @return OrderInterface
     */
    public function getOrder(): OrderInterface;

    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * Returns the foreign id for this order item
     *
     * @return string|null
     */
    public function getFid(): ?string;

    /**
     * @return ProductInterface
     */
    public function getProduct(): ProductInterface;

    /**
     * Returns the quantity for this order item
     *
     * @return integer
     */
    public function getQuantity(): int;
}
