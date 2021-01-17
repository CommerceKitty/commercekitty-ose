<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Warehouse;

use CommerceKitty\Entity\Warehouse\WarehouseEventStore;
use CommerceKitty\HandleTrait;
use CommerceKitty\Message\Command\Warehouse\UpdateWarehouseAddressCommand;
use CommerceKitty\Message\Command\Warehouse\UpdateWarehouseCommand;
use CommerceKitty\Message\Command\Warehouse\UpdateWarehouseNameCommand;
use CommerceKitty\Message\Event\Warehouse\UpdatedNameEvent;
use CommerceKitty\Message\Query\Warehouse\FindWarehouseQuery;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Uid\Ulid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UpdateWarehouseNameCommandHandler implements CommandHandlerInterface
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
        $this->eventBus   = $eventBus;
        $this->queryBus   = $queryBus;
        $this->commandBus = $commandBus;

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @return void
     */
    public function __invoke(UpdateWarehouseNameCommand $message): void
    {
        $this->manager->clear();

        $model = $this->handle(new FindWarehouseQuery($message->getId()));
        if (null === $model) {
            // @todo Logger
            return;
        }

        $payload = [
            'id'   => $message->getId(),
            'name' => $message->getName(),
        ];

        $eventEntity = (new WarehouseEventStore())
            ->setEventId((string) new Ulid())
            ->setEventType('UpdatedName')
            ->setAggregateRootId($message->getId())
            ->setAggregateRootVersion($model->getAggregateRootVersion())
            ->setCreatedAt(new \DateTime())
            ->setPayload($payload)
            ->setMetadata($message->getMetadata())
        ;
        $this->manager->persist($eventEntity);
        $this->manager->flush();

        $this->eventBus->dispatch(new UpdatedNameEvent($payload, $message->getMetadata()));
    }
}
