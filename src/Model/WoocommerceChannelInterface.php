<?php declare(strict_types=1);

namespace App\Model;

/**
 * @see App\Model\WoocommerceChannelTrait::class
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
