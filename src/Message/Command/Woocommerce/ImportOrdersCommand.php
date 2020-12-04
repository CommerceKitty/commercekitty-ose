<?php declare(strict_types=1);

namespace App\Message\Command\Woocommerce;

use App\Message\ChannelAwareTrait;
use App\Message\Command\CommandInterface;

class ImportOrdersCommand implements CommandInterface
{
    use ChannelAwareTrait;
}
