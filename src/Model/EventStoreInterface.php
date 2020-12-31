<?php declare(strict_types=1);

namespace CommerceKitty\Model;

use DateTimeInterface;

interface EventStoreInterface
{
    /**
     * @return string
     */
    public function getEventId(): string;

    /**
     * @param string $eventId
     *
     * @throws Exception
     *
     * @return self
     */
    public function setEventId(string $eventId);

    /**
     * @return string
     */
    public function getEventType(): string;

    /**
     * @param string $eventType
     *
     * @throws Exception
     *
     * @return self
     */
    public function setEventType(string $eventType);

    /**
     * @return string|null
     */
    public function getAggregateRootId(): ?string;

    /**
     * @param string $aggregateRootId
     *
     * @throws Exception
     *
     * @return self
     */
    public function setAggregateRootId(string $aggregateRootId);

    /**
     * @return int
     */
    public function getAggregateRootVersion(): int;

    /**
     * @param integer $aggregateRootVersion
     *
     * @throws Exception
     *
     * @return self
     */
    public function setAggregateRootVersion(int $aggregateRootVersion);

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface;

    /**
     * @param DateTimeInterface $createdAt
     *
     * @throws Exception
     *
     * @return self
     */
    public function setCreatedAt(DateTimeInterface $createdAt);

    /**
     * @return array
     */
    public function getPayload(): array;

    /**
     * @param array $payload
     *
     * @throws Exception
     *
     * @return self
     */
    public function setPayload(array $payload);

    /**
     * @return array
     */
    public function getMetadata(): array;

    /**
     * @param array $metadata
     *
     * @return self
     */
    public function setMetadata(array $metadata);
}
