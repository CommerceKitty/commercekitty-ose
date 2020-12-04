<?php declare(strict_types=1);

namespace CommerceKitty\Model;

/**
 */
interface InventoryInterface
{
    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * Returns the warehouse this record is for
     *
     * @return WarehouseInterface
     */
    public function getWarehouse(): ?WarehouseInterface;

    /**
     * Returns the Product this record is for
     *
     * @return ProductInterface
     */
    public function getProduct(): ?ProductInterface;

    /**
     * Returns the number of Products in the Warehouse
     *
     * @return int
     */
    public function getQuantity(): ?int;
}
