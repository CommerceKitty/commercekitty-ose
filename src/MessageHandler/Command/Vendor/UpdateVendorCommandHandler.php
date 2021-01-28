<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Vendor;

use CommerceKitty\Entity\Vendor\VendorEventStore;
use CommerceKitty\HandleTrait;
use CommerceKitty\Message\Command\Vendor\UpdateVendorCommand;
use CommerceKitty\Message\Command\Vendor\UpdateVendorNameCommand;
use CommerceKitty\Message\Event\Vendor\UpdatedEvent;
use CommerceKitty\Message\Query\Vendor\FindVendorQuery;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Uid\Ulid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UpdateVendorCommandHandler implements CommandHandlerInterface
{
    use HandleTrait;

    private $manager;
    private $commandBus;
    private $eventBus;
    private $dispatcher;
    private $propertyAccessor;

    /**
     */
    public function __construct(EntityManagerInterface $manager, MessageBusInterface $commandBus, MessageBusInterface $eventBus, EventDispatcherInterface $dispatcher, MessageBusInterface $queryBus)
    {
        $this->manager    = $manager;
        $this->commandBus = $commandBus;
        $this->eventBus   = $eventBus;
        $this->dispatcher = $dispatcher;
        $this->queryBus   = $queryBus;

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @return void
     */
    public function __invoke(UpdateVendorCommand $message): void
    {
        $this->manager->clear();

        $model = $this->handle(new FindVendorQuery($message->get('id')));

        // Check if anything has been changed
        $eventPayload = [];
        foreach ($message->getPayload() as $k => $v) {
            $currentValue = $this->propertyAccessor->getValue($model, $k);
            if ($currentValue != $v) {
                $eventPayload[$k] = $v;
            }
        }

        if (empty($eventPayload)) {
            return;
        }

        if (isset($eventPayload['name'])) {
            $this->commandBus->dispatch(new UpdateVendorNameCommand($message->get('id'), $eventPayload['name'], $message->getMetadata()));
        }
    }
}
