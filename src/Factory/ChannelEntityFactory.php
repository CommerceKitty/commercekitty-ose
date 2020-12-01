<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity;

class ChannelEntityFactory
{
    /**
     * @param string $alias
     *
     * @throws Exception
     *
     * @return string
     */
    public function getFullClassName(string $alias): string
    {
        switch ($alias) {
            case('shopify'):
                return Entity\ShopifyChannel::class;
            case('woocommerce'):
                return Entity\WoocommerceChannel::class;
            default:
                throw new \Exception('Channel Type is unknown');
        }
    }
}
