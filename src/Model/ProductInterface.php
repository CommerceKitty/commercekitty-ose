<?php

namespace App\Model;

use DateTimeInterface;

/**
 */
interface ProductInterface
{
    const TYPE_SIMPLE       = 'simple';
    const TYPE_CONFIGURABLE = 'configurable';
    const TYPE_KIT          = 'kit';

    /**
     * @see self::getName()
     */
    public function __toString(): string;

    /**
     * Returns UUID
     *
     * @return string
     */
    public function getId(): ?string;

    /**
     * Returns the name of the product
     *
     * @return string
     */
    public function getName(): ?string;

    /**
     * @return string
     */
    public function getSku(): ?string;

    /**
     * Returns the type of product this is. It can be:
     *   - simple
     *   - configurable
     *   - kit
     *   - virtual
     *   - downloadable
     */
    public function getType(): string;

    //public function getUpc(): ?string;
    //public function getCreatedAt(): DateTimeInterface;
    //public function getCreatedBy(): UserInterface;
    //public function getUpdatedAt(): DateTimeInterface;
    //public function getUpdatedBy(): UserInterface;
}
