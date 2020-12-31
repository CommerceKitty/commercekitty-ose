<?php declare(strict_types=1);

namespace CommerceKitty\Model;

interface PayloadableInterface
{
    /**
     * @return array
     */
    public function toPayload(): array;
}
