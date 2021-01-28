<?php declare(strict_types=1);

namespace CommerceKitty\Message\Event\Vendor;

use CommerceKitty\Message\Event\EventInterface;
use CommerceKitty\Message\MetadataTrait;
use CommerceKitty\Message\PayloadTrait;

class PurgedEvent implements EventInterface
{
    use MetadataTrait;
    use PayloadTrait;

    /**
     */
    public function __construct(array $payload, array $metadata = [])
    {
        $this->payload  = $payload;
        $this->metadata = $metadata;
    }
}
