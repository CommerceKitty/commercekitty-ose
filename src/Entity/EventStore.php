<?php

namespace CommerceKitty\Entity;

use CommerceKitty\Model\AbstractEventStore;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
class EventStore extends AbstractEventStore
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=26, unique=true)
     */
    protected $eventId;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $eventType;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    protected $aggregateRootId;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $aggregateRootVersion;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="array", nullable=false)
     */
    protected $payload;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $metadata;
}
