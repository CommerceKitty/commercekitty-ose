<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query\User;

use CommerceKitty\Message\Query\FindTrait;
use CommerceKitty\Message\Query\QueryInterface;

class FindUserQuery implements QueryInterface
{
    use FindTrait;
}
