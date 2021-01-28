<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query\Vendor;

use CommerceKitty\Message\Query\FindByTrait;
use CommerceKitty\Message\Query\QueryInterface;

class FindByVendorQuery implements QueryInterface
{
    use FindByTrait;
}
