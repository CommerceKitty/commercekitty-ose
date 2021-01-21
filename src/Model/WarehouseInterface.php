<?php declare(strict_types=1);

namespace CommerceKitty\Model;

/**
 */
interface WarehouseInterface
{
    /**
     * Returns the Unique ID of this warehouse
     *
     * @return string
     */
    public function getId(): ?string;

    /**
     * @param string $id
     *
     * @throws Exception
     *
     * @return self
     */
    public function setId(string $id);

    /**
     * Returns the Warehouse Name
     *
     * @return string
     */
    public function getName(): ?string;

    /**
     * @todo
     *
     * @return AddressInterface|null
     */
    //public function getAddress(): ?AddressInterface;
}
