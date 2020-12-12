<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query\Product;

use CommerceKitty\Message\Query\FindByTrait;
use CommerceKitty\Message\Query\QueryInterface;

class FindByProductQuery implements QueryInterface
{
    use FindByTrait;
}
