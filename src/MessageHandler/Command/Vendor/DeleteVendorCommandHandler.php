<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Vendor;

use CommerceKitty\Entity\Vendor\VendorEventStore;
use CommerceKitty\HandleTrait;
use CommerceKitty\Message\Command\Vendor\DeleteVendorCommand;
use CommerceKitty\Message\Event\Vendor\DeletedEvent;
use CommerceKitty\Message\Query\Vendor\FindVendorQuery;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Uid\Ulid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class DeleteVendorCommandHandler implements CommandHandlerInterface
{
    use HandleTrait;

    private $manager;
    private $eventBus;
    private $propertyAccessor;
    private $dispatcher;

    /**
     */
    public function __construct(EntityManagerInterface $manager, MessageBusInterface $eventBus, MessageBusInterface $queryBus, EventDispatcherInterface $dispatcher)
    {
        $this->manager    = $manager;
        $this->eventBus   = $eventBus;
        $this->queryBus   = $queryBus;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return void
     */
    public function __invoke(DeleteVendorCommand $message): void
    {
        $this->manager->clear();

        $model = $this->handle(new FindVendorQuery($message->get('id')));

        $eventEntity = (new VendorEventStore())
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
        $this->dispatcher->dispatch(new GenericEvent($eventEntity), 'vendor.deleted');
    }
}
