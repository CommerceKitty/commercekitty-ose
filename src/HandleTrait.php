<?php declare(strict_types=1);

namespace CommerceKitty;

use Symfony\Component\Messenger\Exception\LogicException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 */
trait HandleTrait
{
    /**
     * @var MessageBusInterface
     */
    protected $queryBus;

    /**
     * @param QueryMessageInterface $query
     *
     * @throws LogicException
     *
     * @return mixed
     */
    public function handle($query)
    {
        if (!$this->queryBus instanceof MessageBusInterface) {
            throw new LogicException(sprintf('You must provide a "%s" instance in the "%s::$queryBus" property, "%s" given.', MessageBusInterface::class, static::class, get_debug_type($this->queryBus)));
        }

        $envelope     = $this->queryBus->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);
        $result       = $handledStamp->getResult();

        return $result;
    }
}
