<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Warehouse;

use CommerceKitty\Entity\Warehouse\WarehouseEventStore;
use CommerceKitty\HandleTrait;
use CommerceKitty\Message\Command\Warehouse\DeleteWarehouseCommand;
use CommerceKitty\Message\Command\Warehouse\PurgeWarehouseCommand;
use CommerceKitty\Message\Event\Warehouse\PurgedWarehouseEvent;
use CommerceKitty\Message\Query\Warehouse\FindByWarehouseQuery;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Uid\Ulid;

class PurgeWarehouseCommandHandler implements CommandHandlerInterface
{
    use HandleTrait;

    private $manager;
    private $commandBus;
    private $eventBus;
    private $propertyAccessor;

    /**
     */
    public function __construct(EntityManagerInterface $manager, MessageBusInterface $commandBus, MessageBusInterface $eventBus, MessageBusInterface $queryBus)
    {
        $this->manager    = $manager;
        $this->commandBus = $commandBus;
        $this->eventBus   = $eventBus;
        $this->queryBus   = $queryBus;
    }

    /**
     * @return void
     */
    public function __invoke(PurgeWarehouseCommand $message): void
    {
        $this->manager->clear();

        $collection = $this->handle(new FindByWarehouseQuery());
        if (empty($collection)) {
            return;
        }

        foreach ($collection as $entity) {
            $this->commandBus->dispatch(new DeleteWarehouseCommand(['id' => $entity->getId()], $message->getMetadata()));
        }

        $eventEntity = (new WarehouseEventStore())
            ->setEventId((string) new Ulid())
            ->setEventType('Purged')
            //->setAggregateRootId($message->get('id'))
            ->setAggregateRootVersion(0)
            ->setCreatedAt(new \DateTime())
            ->setPayload($message->getPayload())
            ->setMetadata($message->getMetadata())
        ;

        $this->eventBus->dispatch(new PurgedWarehouseEvent($message->getPayload(), $message->getMetadata()));
    }
}
