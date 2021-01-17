<?php declare(strict_types=1);

namespace CommerceKitty\Message\Command\Warehouse;

use CommerceKitty\Message\Command\CommandInterface;
use CommerceKitty\Message\MetadataTrait;
use CommerceKitty\Message\PayloadTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class UpdateWarehouseAddressCommand implements CommandInterface
{
    use MetadataTrait;
    use PayloadTrait;

    private $id;

    /**
     */
    public function __construct(string $id, array $payload, array $metadata = [])
    {
        $this->payload  = $payload;
        $this->metadata = $metadata;
        $this->id       = $id;
    }

    /**
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('id', new Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Uuid(),
        ]));
    }
}
