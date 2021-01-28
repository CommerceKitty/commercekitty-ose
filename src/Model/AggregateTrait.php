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
     * @throws BadMethodCallException
     *
     * @return void
     */
    public function apply(EventStoreInterface $event): void
    {
        $method = 'apply'.$event->getEventType();

        if (!method_exists($this, $method)) {
            // @todo throw new \CommerceKitty\Exception\BadMethodCallException(static::class, $method, $previous = null);
            throw new \BadMethodCallException(sprintf('Class "%s" must define the method "%s"', static::class, $method));
        }

        $this->$method($event);
        ++$this->aggregateRootVersion;
    }
}
