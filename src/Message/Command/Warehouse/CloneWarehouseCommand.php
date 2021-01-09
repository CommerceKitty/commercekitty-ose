<?php declare(strict_types=1);

namespace CommerceKitty\Message\Command\Warehouse;

use CommerceKitty\Message\Command\CommandInterface;
use CommerceKitty\Message\MetadataTrait;
use CommerceKitty\Message\PayloadTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class CloneWarehouseCommand implements CommandInterface
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

    /**
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('payload', new Assert\Collection([
            'allowExtraFields' => true,
            'missingFieldsMessage' => 'Payload must contain {{ field }}.',
            'fields' => [
                'id'       => [new Assert\NotBlank(), new Assert\Uuid()],
                'clone_id' => [new Assert\NotBlank(), new Assert\Uuid()],
            ],
        ]));
    }
}
