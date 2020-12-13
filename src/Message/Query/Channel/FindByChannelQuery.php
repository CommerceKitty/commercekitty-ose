<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query\Channel;

use CommerceKitty\Message\Query\FindByTrait;
use CommerceKitty\Message\Query\QueryInterface;

class FindByChannelQuery implements QueryInterface
{
    use FindByTrait;
}
