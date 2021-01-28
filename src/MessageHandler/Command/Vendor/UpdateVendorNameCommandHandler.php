<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Vendor;

use CommerceKitty\Entity\Vendor\VendorEventStore;
use CommerceKitty\HandleTrait;
use CommerceKitty\Message\Command\Vendor\UpdateVendorNameCommand;
use CommerceKitty\Message\Event\Vendor\UpdatedNameEvent;
use CommerceKitty\Message\Query\Vendor\FindVendorQuery;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Uid\Ulid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UpdateVendorNameCommandHandler implements CommandHandlerInterface
{
    use HandleTrait;

    private $manager;
    private $eventBus;
    private $dispatcher;

    private $propertyAccessor;

    /**
     */
    public function __construct(EntityManagerInterface $manager, MessageBusInterface $eventBus, MessageBusInterface $queryBus, EventDispatcherInterface $dispatcher)
    {
        $this->manager    = $manager;
        $this->eventBus   = $eventBus;
        $this->queryBus   = $queryBus;
        $this->dispatcher = $dispatcher;

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @return void
     */
    public function __invoke(UpdateVendorNameCommand $message): void
    {
        $this->manager->clear();

        $model = $this->handle(new FindVendorQuery($message->getId()));
        if (null === $model) {
            // @todo Logger
            return;
        }

        $payload = [
            'id'   => $message->getId(),
            'name' => $message->getName(),
        ];

        $eventEntity = (new VendorEventStore())
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
        $this->dispatcher->dispatch(new GenericEvent($eventEntity), 'vendor.updated.name');
    }
}
