<?php declare(strict_types=1);

namespace CommerceKitty\Message\Command\User;

use CommerceKitty\Message\PayloadTrait;
use CommerceKitty\Message\Command\CommandInterface;

class ChangePasswordCommand implements CommandInterface
{
    use PayloadTrait;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
