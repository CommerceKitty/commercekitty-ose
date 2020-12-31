<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Warehouse;

use CommerceKitty\Entity\Warehouse\WarehouseEventStore;
use CommerceKitty\Message\Command\Warehouse\CreateWarehouseCommand;
use CommerceKitty\Message\Event\Warehouse\CreatedWarehouseEvent;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Uid\Ulid;

class CreateWarehouseCommandHandler implements CommandHandlerInterface
{
    private $manager;
    private $eventBus;

    /**
     */
    public function __construct(EntityManagerInterface $manager, MessageBusInterface $eventBus)
    {
        $this->manager  = $manager;
        $this->eventBus = $eventBus;
    }

    /**
     * @return void
     */
    public function __invoke(CreateWarehouseCommand $message): void
    {
        $this->manager->clear();

        // Can make a trait or something for this, clears out any values
        // that are null/empty
        $eventPayload = [];
        foreach ($message->getPayload() as $key => $value) {
            if (!empty($value)) {
                $eventPayload[$key] = $value;
            }
        }

        $eventEntity = (new WarehouseEventStore())
            ->setEventId((string) new Ulid())
            ->setEventType('CreatedWarehouseEvent')
            ->setAggregateRootId($message->get('id'))
            ->setAggregateRootVersion(0)
            ->setCreatedAt(new \DateTime())
            ->setPayload($eventPayload)
            ->setMetadata($message->getMetadata())
        ;
        $this->manager->persist($eventEntity);
        $this->manager->flush();

        $this->eventBus->dispatch(new CreatedWarehouseEvent($eventPayload));
    }
}
