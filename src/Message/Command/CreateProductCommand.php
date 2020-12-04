<?php declare(strict_types=1);

namespace CommerceKitty\Message\Command;

use CommerceKitty\Message\ChannelAwareTrait;
use CommerceKitty\Message\Command\CommandInterface;

class CreateProductCommand implements CommandInterface
{
    use ChannelAwareTrait;

    protected $name;
    protected $sku;
    protected $type;

    public function __construct(string $type, string $name, string $sku)
    {
        $this->type = $type;
        $this->name = $name;
        $this->sku  = $sku;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSku(): string
    {
        return $this->sku;
    }
}
