<?php declare(strict_types=1);

namespace App\Factory;

use App\Message;
use App\Model\ChannelInterface;

class ChannelMessageFactory
{
    /**
     * @param ChannelInterface $channel
     *
     * @throws Exception
     *
     * @return
     */
    public function getImportProductsMessage(ChannelInterface $channel)
    {
        switch ($channel->getType()) {
            case('shopify'):
                return new Message\Event\Shopify\ImportProductsMessage();
            case('woocommerce'):
                return new Message\Event\Woocommerce\ImportProductsMessage();
        }

        throw new \Exception('Channel Type is Unknown');
    }

    /**
     * @param ChannelInterface $channel
     *
     * @throws Exception
     *
     * @return
     */
    public function getImportOrdersMessage(ChannelInterface $channel)
    {
        switch ($channel->getType()) {
            case('shopify'):
                return new Message\Event\Shopify\ImportOrdersMessage();
            case('woocommerce'):
                return new Message\Event\Woocommerce\ImportOrdersMessage();
        }

        throw new \Exception('Channel Type is Unknown');
    }

    /**
     * @param ChannelInterface $channel
     *
     * @throws Exception
     *
     * @return
     */
    public function getExportInventoryMessage(ChannelInterface $channel)
    {
        switch ($channel->getType()) {
            case('shopify'):
                return new Message\Event\Shopify\ExportInventoryMessage();
            case('woocommerce'):
                return new Message\Event\Woocommerce\ExportInventoryMessage();
        }

        throw new \Exception('Channel Type is Unknown');
    }

    /**
     * @param ChannelInterface $channel
     *
     * @throws Exception
     *
     * @return
     */
    public function getExportListingsMessage(ChannelInterface $channel)
    {
        switch ($channel->getType()) {
            case('shopify'):
                return new Message\Event\Shopify\ExportListingsMessage();
            case('woocommerce'):
                return new Message\Event\Woocommerce\ExportListingsMessage();
        }

        throw new \Exception('Channel Type is Unknown');
    }
}
