<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query\Product;

use CommerceKitty\Message\Query\FindTrait;
use CommerceKitty\Message\Query\QueryInterface;

class FindProductQuery implements QueryInterface
{
    use FindTrait;
}
