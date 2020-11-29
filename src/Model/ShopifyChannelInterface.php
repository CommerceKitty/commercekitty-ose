<?php declare(strict_types=1);

namespace App\Model;

/**
 * @see App\Model\ShopifyChannelTrait::class
 */
interface ShopifyChannelInterface
{
    /**
     * @return string
     */
    public function getHost(): ?string;

    /**
     * @return string
     */
    public function getApiKey(): ?string;

    /**
     * @return string
     */
    public function getPassword(): ?string;
}
