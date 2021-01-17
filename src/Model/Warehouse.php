<?php declare(strict_types=1);

namespace CommerceKitty\Model;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 */
class Warehouse implements WarehouseInterface, PayloadableInterface
{
    use AggregateTrait;

    protected $id;
    protected $name;
    protected $address;

    /**
     */
    public function __construct()
    {
        $this->address = new Address();
    }

    /**
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId(string $id)
    {
        if ($this->id && $this->id != $id) {
            throw new \Exception('ID Cannot Be Changed');
        }

        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress(): AddressInterface
    {
        return $this->address;
    }

    /**
     */
    public function hasAddress(): bool
    {
        return ($this->address instanceof AddressInterface);
    }

    /**
     */
    public function setAddress(AddressInterface $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toPayload(): array
    {
        $payload = [
            'id'   => $this->id,
            'name' => $this->name,
        ];

        //if ($this->hasAddress()) {
        //    $payload['address'] = $this->address->toPayload();
        //}

        return $payload;
    }

    // ---

    /**
     */
    public function applyCreated(EventStoreInterface $event): void
    {
        $payload = $event->getPayload();

        $this->id   = $payload['id'];
        $this->name = $payload['name'];

        if (!empty($payload['address'])) {
            $this->address = Address::fromPayload($payload['address']);
        }
    }

    /**
     */
    public function applyUpdated(EventStoreInterface $event): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($event->getPayload() as $k => $v) {
            $propertyAccessor->setValue($this, $k, $v);
        }
    }

    /**
     */
    public function applyDeleted(EventStoreInterface $event): void
    {
    }

    /**
     */
    public function applyUpdatedName(EventStoreInterface $event): void
    {
        $payload = $event->getPayload();

        $this->name = $payload['name'];
    }
}
