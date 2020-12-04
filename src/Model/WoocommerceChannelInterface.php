<?php declare(strict_types=1);

namespace CommerceKitty\Model;

/**
 * @see CommerceKitty\Model\WoocommerceChannelTrait::class
 */
interface WoocommerceChannelInterface
{
    /**
     * @return string
     */
    public function getHost(): ?string;

    /**
     * @return string
     */
    public function getConsumerKey(): ?string;

    /**
     * @return string
     */
    public function getConsumerSecret(): ?string;
}
