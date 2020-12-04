<?php declare(strict_types=1);

namespace App\Message\Command\Shopify;

use App\Message\ChannelAwareTrait;
use App\Message\Command\CommandInterface;

class ExportInventoryCommand implements CommandInterface
{
    use ChannelAwareTrait;
}
