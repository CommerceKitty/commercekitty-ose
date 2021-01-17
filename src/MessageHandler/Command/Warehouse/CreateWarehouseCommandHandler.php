<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Warehouse;

use CommerceKitty\Entity\Warehouse\WarehouseEventStore;
use CommerceKitty\Message\Command\Warehouse\CreateWarehouseCommand;
use CommerceKitty\Message\Event\Warehouse\CreatedWarehouseEvent;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Uid\Ulid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CreateWarehouseCommandHandler implements CommandHandlerInterface
{
    private $manager;
    private $eventBus;
    private $dispatcher;

    /**
     */
    public function __construct(EntityManagerInterface $manager, MessageBusInterface $commandBus, MessageBusInterface $eventBus, EventDispatcherInterface $dispatcher)
    {
        $this->manager    = $manager;
        $this->commandBus = $commandBus;
        $this->eventBus   = $eventBus;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return void
     */
    public function __invoke(CreateWarehouseCommand $message): void
    {
        $this->manager->clear();

        // Can make a trait or something for this, clears out any values
        // that are null/empty, ONLY needed on initial create, update can
        // have null/empty/false values
        $eventPayload = [];
        foreach ($message->getPayload() as $key => $value) {
            if (!empty($value)) {
                $eventPayload[$key] = $value;
            }
        }

        // @todo Generic Builder?
        $eventEntity = (new WarehouseEventStore())
            ->setEventId((string) new Ulid())
            ->setEventType('Created')
            ->setAggregateRootId($message->get('id'))
            ->setAggregateRootVersion(0)
            ->setCreatedAt(new \DateTime())
            ->setPayload($eventPayload)
            ->setMetadata($message->getMetadata())
        ;
        $this->manager->persist($eventEntity);
        $this->manager->flush();

        // Dispatch the event to the bus
        $this->eventBus->dispatch(new CreatedWarehouseEvent($eventPayload, $message->getMetadata()));

        // This is here because there will be times when we need to do things that if an event
        // is re-played, we also do not want to do. Think sending emails.
        $this->dispatcher->dispatch(new GenericEvent($eventEntity), 'warehouse.created');
    }
}
