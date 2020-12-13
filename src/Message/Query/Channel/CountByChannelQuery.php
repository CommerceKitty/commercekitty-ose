<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query\Channel;

use CommerceKitty\Message\Query\CountByTrait;
use CommerceKitty\Message\Query\QueryInterface;

class CountByChannelQuery implements QueryInterface
{
    use CountByTrait;
}
