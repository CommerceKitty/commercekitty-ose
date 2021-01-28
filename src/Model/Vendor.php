<?php declare(strict_types=1);

namespace CommerceKitty\Model;

/**
 */
class Vendor implements VendorInterface, PayloadableInterface
{
    use AggregateTrait;

    protected $id;
    protected $name;

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
     */
    public function toPayload(): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }

    // ---

    /**
     */
    public function applyCreated(EventStoreInterface $event): void
    {
        $payload = $event->getPayload();

        $this
            ->setId($payload['id'])
            ->setName($payload['name'])
        ;
    }

    /**
     */
    public function applyUpdatedName(EventStoreInterface $event): void
    {
        $payload = $event->getPayload();

        $this
            ->setName($payload['name'])
        ;
    }

    /**
     */
    public function applyDeleted(EventStoreInterface $event): void
    {
    }
}
