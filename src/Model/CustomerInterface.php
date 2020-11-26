<?php declare(strict_types=1);

namespace App\Model;

/**
 */
interface CustomerInterface
{
    /**
     * @return string
     */
    public function getId(): ?string;

    public function getEmail(): ?string;

    // AddressInterface = ShippingAddress
    // AddressInterface = BillingAddress
}
