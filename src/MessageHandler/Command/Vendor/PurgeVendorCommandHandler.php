<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Vendor;

use CommerceKitty\Entity\Vendor\VendorEventStore;
use CommerceKitty\HandleTrait;
use CommerceKitty\Message\Command\Vendor\DeleteVendorCommand;
use CommerceKitty\Message\Command\Vendor\PurgeVendorCommand;
use CommerceKitty\Message\Event\Vendor\PurgedEvent;
use CommerceKitty\Message\Query\Vendor\FindByVendorQuery;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Uid\Ulid;

class PurgeVendorCommandHandler implements CommandHandlerInterface
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
    public function __invoke(PurgeVendorCommand $message): void
    {
        $this->manager->clear();

        $collection = $this->handle(new FindByVendorQuery());
        if (empty($collection)) {
            return;
        }

        $eventEntity = (new VendorEventStore())
            ->setEventId((string) new Ulid())
            ->setEventType('Purged')
            //->setAggregateRootId($message->get('id'))
            ->setAggregateRootVersion(0)
            ->setCreatedAt(new \DateTime())
            ->setPayload($message->getPayload())
            ->setMetadata($message->getMetadata())
        ;
        $this->manager->persist($eventEntity);
        $this->manager->flush();

        foreach ($collection as $entity) {
            $this->commandBus->dispatch(new DeleteVendorCommand(['id' => $entity->getId()], $message->getMetadata()));
        }

        $this->eventBus->dispatch(new PurgedEvent($message->getPayload(), $message->getMetadata()));
    }
}
