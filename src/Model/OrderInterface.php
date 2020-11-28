<?php declare(strict_types=1);

namespace App\Model;

/**
 */
interface OrderInterface
{
    /**
     * Returns the Marketplace Channel this order is from
     *
     * @return ChannelInterface
     */
    public function getChannel(): ChannelInterface;

    /**
     * @return OrderItemInterface[]
     */
    public function getOrderItems();

    /**
     * @return CustomerInterface
     */
    public function getCustomer(): CustomerInterface;

    /**
     * Returns the unique id for use within the app
     *
     * @return string
     */
    public function getId(): ?string;

    /**
     * Returns the foreign id for this order
     *
     * @return string
     */
    public function getFid(): string;

    /**
     * Returns the display id
     *
     * @return string
     */
    public function getDisplayId(): string;

    // AddressInterface = BillingAddress
    // AddressInterface = ShippingAddress
}
