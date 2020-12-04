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
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return ProductInterface
     */
    public function getProduct(): ?ProductInterface;
}
