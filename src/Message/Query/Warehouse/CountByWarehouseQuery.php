<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query\Warehouse;

use CommerceKitty\Message\Query\CountByTrait;
use CommerceKitty\Message\Query\QueryInterface;

class CountByWarehouseQuery implements QueryInterface
{
    use CountByTrait;
}
