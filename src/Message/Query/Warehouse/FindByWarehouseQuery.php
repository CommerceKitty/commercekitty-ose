<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query\Warehouse;

use CommerceKitty\Message\Query\FindByTrait;
use CommerceKitty\Message\Query\QueryInterface;

class FindByWarehouseQuery implements QueryInterface
{
    use FindByTrait;
}
