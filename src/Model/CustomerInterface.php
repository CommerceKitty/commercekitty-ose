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

    /**
     * @return string
     */
    public function getFirstName(): ?string;

    /**
     * @return string
     */
    public function getLastName(): ?string;

    /**
     * @return string
     */
    public function getEmail(): ?string;

    /**
     * @return string
     */
    public function getPhone(): ?string;

    /**
     * @return AddressInterface|null
     */
    public function getShippingAddress(): ?AddressInterface;

    /**
     * @return AddressInterface|null
     */
    public function getBillingAddress(): ?AddressInterface;
}
