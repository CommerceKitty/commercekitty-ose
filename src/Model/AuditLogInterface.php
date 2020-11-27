<?php declare(strict_types=1);

namespace App\Model;

/**
 */
interface AuditLogInterface
{
    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * Returns snake case entity name, example "OrderItem" would be "order_item"
     *
     * @return string
     */
    public function getEntity(): string;

    /**
     * Returns the ID of the entity this log entry is for
     *
     * @return string
     */
    public function getEntityId(): string;

    /**
     * The event that took place
     *
     * @return string
     */
    public function getEventName(): string;

    /**
     * Returns the timestamp of the event
     *
     * @return DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface;
}
