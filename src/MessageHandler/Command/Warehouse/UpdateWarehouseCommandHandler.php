<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Warehouse;

use CommerceKitty\Entity\Warehouse\WarehouseEventStore;
use CommerceKitty\HandleTrait;
use CommerceKitty\Message\Command\Warehouse\UpdateWarehouseCommand;
use CommerceKitty\Message\Event\Warehouse\UpdatedWarehouseEvent;
use CommerceKitty\Message\Query\Warehouse\FindWarehouseQuery;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Uid\Ulid;

class UpdateWarehouseCommandHandler implements CommandHandlerInterface
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

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @return void
     */
    public function __invoke(UpdateWarehouseCommand $message): void
    {
        $this->manager->clear();

        // What has changed?
        $eventPayload = [];
        $model = $this->handle(new FindWarehouseQuery($message->get('id')));
        foreach ($message->getPayload() as $k => $v) {
            if ('address' === $k) {
                continue;
            }

            $currentValue = $this->propertyAccessor->getValue($model, $k);
            if ($currentValue != $v) {
                $eventPayload[$k] = $v;
            }
        }

        // diff on the address
        if ($message->has('address')) {
            foreach ($message->get('address') as $k => $v) {
                $currentValue = $this->propertyAccessor->getValue($model->getAddress(), $k);
                if ($currentValue != $v) {
                    $eventPayload['address'][$k] = $v;
                }
            }
        }

        if (empty($eventPayload)) {
            // Nothing was changed
            return;
        }

        $eventEntity = (new WarehouseEventStore())
            ->setEventId((string) new Ulid())
            ->setEventType('UpdatedWarehouseEvent')
            ->setAggregateRootId($message->get('id'))
            ->setAggregateRootVersion($model->getAggregateRootVersion())
            ->setCreatedAt(new \DateTime())
            ->setPayload($eventPayload)
            ->setMetadata($message->getMetadata())
        ;
        $this->manager->persist($eventEntity);
        $this->manager->flush();

        $eventPayload['id'] = $message->get('id'); // Must have
        $this->eventBus->dispatch(new UpdatedWarehouseEvent($eventPayload));
    }
}
