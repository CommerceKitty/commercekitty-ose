<?php declare(strict_types=1);

namespace App\Model;

/**
 */
interface VendorInterface
{
    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getName(): ?string;
}
