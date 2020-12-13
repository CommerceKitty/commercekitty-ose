<?php declare(strict_types=1);

namespace CommerceKitty\Message\Command\Channel;

use CommerceKitty\Message\PayloadTrait;
use CommerceKitty\Message\Command\CommandInterface;

class UpdateChannelCommand implements CommandInterface
{
    use PayloadTrait;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
