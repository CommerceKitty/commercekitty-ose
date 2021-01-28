<?php declare(strict_types=1);

namespace CommerceKitty\Message\Command\Vendor;

use CommerceKitty\Message\Command\CommandInterface;
use CommerceKitty\Message\MetadataTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class UpdateVendorNameCommand implements CommandInterface
{
    use MetadataTrait;

    private $id;
    private $name;

    /**
     */
    public function __construct(string $id, string $name, array $metadata = [])
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->metadata = $metadata;
    }

    /**
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     */
    public function getName(): string
    {
        return $this->name;
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

        $metadata->addPropertyConstraint('name', new Assert\Type('string'));
    }
}
