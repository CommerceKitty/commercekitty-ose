<?php declare(strict_types=1);

namespace App\Message\Event\Woocommerce;

use App\Message\ChannelAwareTrait;

class ImportOrdersMessage
{
    use ChannelAwareTrait;
}
