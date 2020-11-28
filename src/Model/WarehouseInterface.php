<?php declare(strict_types=1);

namespace App\Model;

/**
 */
interface WarehouseInterface
{
    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getName(): ?string;

    /**
     * @return AddressInterface|null
     */
    public function getAddress(): ?AddressInterface;
}
