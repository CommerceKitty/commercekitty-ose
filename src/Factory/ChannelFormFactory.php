<?php declare(strict_types=1);

namespace App\Factory;

use App\Form\Type;

class ChannelFormFactory
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
                return Type\ShopifyChannelType::class;
            case('woocommerce'):
                return Type\WoocommerceChannelType::class;
            default:
                throw new \Exception('Channel Type is unknown');
        }
    }
}
