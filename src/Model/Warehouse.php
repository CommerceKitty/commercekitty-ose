<?php declare(strict_types=1);

namespace CommerceKitty\Model;

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
        if ($this->id) {
            throw new \Exception('');
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
    public function getAddress(): ?AddressInterface
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
    public function apply($event)
    {
        $method = 'apply'.$event->getEventType();
        $this->$method($event);
        ++$this->aggregateRootVersion;
    }

    /**
     */
    public function applyCreatedWarehouseEvent($event)
    {
        $payload    = $event->getPayload();
        $this->id   = $payload['id'];
        $this->name = $payload['name'];
    }
}
