<?php declare(strict_types=1);

namespace CommerceKitty\Message\Command\Shopify;

use CommerceKitty\Message\ChannelAwareTrait;
use CommerceKitty\Message\Command\CommandInterface;

class ExportInventoryCommand implements CommandInterface
{
    use ChannelAwareTrait;
}
