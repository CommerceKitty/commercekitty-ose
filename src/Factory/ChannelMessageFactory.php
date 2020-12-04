<?php declare(strict_types=1);

namespace CommerceKitty\Factory;

use CommerceKitty\Message\Command\CommandInterface;
use CommerceKitty\Model\ChannelInterface;
use function Symfony\Component\String\u;

class ChannelMessageFactory
{
    /**
     * @param string           $command
     * @param ChannelInterface $channel
     *
     * @throws Exception
     *
     * @return CommandInterface
     */
    public function getCommand(string $command, ChannelInterface $channel): CommandInterface
    {
        $type                 = $channel->getType();
        $commandClassName     = $command.'Command';
        $commandNamespace     = 'CommerceKitty\\Message\\Command\\'.u($type)->lower()->camel()->title()->toString();
        $commandFullClassName = $commandNamespace.'\\'.$commandClassName;

        if (!class_exists($commandFullClassName)) {
            throw new \Exception('Command "'.$commandFullClassName.'" Not Found.');
        }

        return new $commandFullClassName();
    }

    /**
     * @param ChannelInterface $channel
     *
     * @throws Exception
     *
     * @return
     */
    public function getImportProductsMessage(ChannelInterface $channel)
    {
        return $this->getCommand('ImportProducts', $channel);
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
        return $this->getCommand('ImportOrders', $channel);
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
        return $this->getCommand('ExportInventory', $channel);
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
        return $this->getCommand('ExportListings', $channel);
    }
}
