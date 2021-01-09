<?php declare(strict_types=1);

namespace CommerceKitty\Model;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 */
class Warehouse implements WarehouseInterface, PayloadableInterface
{
    private $aggregateRootVersion = 0;

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

        if ($this->hasAddress()) {
            $payload['address'] = $this->address->toPayload();
        }

        return $payload;
    }

    // ---

    /**
     */
    public function getAggregateRootVersion() { return $this->aggregateRootVersion; }

    /**
     */
    public function apply(EventStoreInterface $event): void
    {
        $method = 'apply'.$event->getEventType();
        $this->$method($event);
        ++$this->aggregateRootVersion;
    }

    /**
     */
    public function applyCreatedWarehouseEvent(EventStoreInterface $event): void
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
    public function applyUpdatedWarehouseEvent(EventStoreInterface $event): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($event->getPayload() as $k => $v) {
            $propertyAccessor->setValue($this, $k, $v);
        }
    }
}
