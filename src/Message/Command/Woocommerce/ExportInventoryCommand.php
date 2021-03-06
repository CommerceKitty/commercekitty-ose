<?php declare(strict_types=1);

namespace CommerceKitty\Message\Command\Woocommerce;

use CommerceKitty\Message\ChannelAwareTrait;
use CommerceKitty\Message\Command\CommandInterface;

class ExportInventoryCommand implements CommandInterface
{
    use ChannelAwareTrait;
}
