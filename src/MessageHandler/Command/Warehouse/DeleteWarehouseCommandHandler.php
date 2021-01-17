<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Warehouse;

use CommerceKitty\Entity\Warehouse\WarehouseEventStore;
use CommerceKitty\HandleTrait;
use CommerceKitty\Message\Command\Warehouse\DeleteWarehouseCommand;
use CommerceKitty\Message\Event\Warehouse\DeletedEvent;
use CommerceKitty\Message\Query\Warehouse\FindWarehouseQuery;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Uid\Ulid;

class DeleteWarehouseCommandHandler implements CommandHandlerInterface
{
    use HandleTrait;

    private $manager;
    private $eventBus;
    private $propertyAccessor;

    /**
     */
    public function __construct(EntityManagerInterface $manager, MessageBusInterface $eventBus, MessageBusInterface $queryBus)
    {
        $this->manager  = $manager;
        $this->eventBus = $eventBus;
        $this->queryBus = $queryBus;
    }

    /**
     * @return void
     */
    public function __invoke(DeleteWarehouseCommand $message): void
    {
        $this->manager->clear();

        $model = $this->handle(new FindWarehouseQuery($message->get('id')));

        $eventEntity = (new WarehouseEventStore())
            ->setEventId((string) new Ulid())
            ->setEventType('Deleted')
            ->setAggregateRootId($message->get('id'))
            ->setAggregateRootVersion($model->getAggregateRootVersion())
            ->setCreatedAt(new \DateTime())
            ->setPayload($message->getPayload())
            ->setMetadata($message->getMetadata())
        ;
        $this->manager->persist($eventEntity);
        $this->manager->flush();

        $this->eventBus->dispatch(new DeletedEvent($message->getPayload(), $message->getMetadata()));
    }
}
