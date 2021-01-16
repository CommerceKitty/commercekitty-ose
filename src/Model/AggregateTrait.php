<?php declare(strict_types=1);

namespace CommerceKitty\Model;

trait AggregateTrait
{
    /**
     * @var int
     */
    private $aggregateRootVersion = 0;

    /**
     * @return int
     */
    public function getAggregateRootVersion(): int
    {
        return $this->aggregateRootVersion;
    }

    /**
     * @param EventStoreInterface $event
     *
     * @return void
     */
    public function apply(EventStoreInterface $event): void
    {
        $method = 'apply'.$event->getEventType();
        $this->$method($event);
        ++$this->aggregateRootVersion;
    }
}
