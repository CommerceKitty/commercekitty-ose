<?php declare(strict_types=1);

namespace CommerceKitty\Message\Event\Product;

use CommerceKitty\Message\Event\EventInterface;
use CommerceKitty\Message\PayloadTrait;

class CreatedProductEvent implements EventInterface
{
    use PayloadTrait;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
