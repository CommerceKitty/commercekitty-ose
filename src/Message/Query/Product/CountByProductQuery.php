<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query\Product;

use CommerceKitty\Message\Query\CountByTrait;
use CommerceKitty\Message\Query\QueryInterface;

class CountByProductQuery implements QueryInterface
{
    use CountByTrait;
}
