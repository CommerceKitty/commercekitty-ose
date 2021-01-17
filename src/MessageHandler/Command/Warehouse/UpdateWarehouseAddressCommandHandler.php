<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Warehouse;

use CommerceKitty\Entity\Warehouse\WarehouseEventStore;
use CommerceKitty\HandleTrait;
use CommerceKitty\Message\Command\Warehouse\UpdateWarehouseAddressCommand;
use CommerceKitty\Message\Event\Warehouse\UpdatedWarehouseEvent;
use CommerceKitty\Message\Query\Warehouse\FindWarehouseQuery;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Uid\Ulid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UpdateWarehouseAddressCommandHandler implements CommandHandlerInterface
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
    public function __invoke(UpdateWarehouseAddressCommand $message): void
    {
        $this->manager->clear();

        // @var WarehouseInterface|null
        $model = $this->handle(new FindWarehouseQuery($message->getId()));
        if (null === $model) {
            // @todo Logger
            return;
        }

        // @var AddressInterface
        $address      = $model->getAddress();
        $eventPayload = [];
        foreach ($message->getPayload() as $k => $v) {
            $currentValue = $this->propertyAccessor->getValue($address, $k);
            if ($currentValue != $v) {
                $eventPayload[$k] = $v;
            }
        }

        //dd($address, $message->getPayload(), $eventPayload);

        // ---

        // @todo Make this a trait or something as this will be the same except for the command
        if (isset($eventPayload['phone'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressPhoneCommand($message->getId(), $eventPayload['phone'], $message->getMetadata()));
        }

        if (isset($eventPayload['first_name'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressFirstNameCommand($message->getId(), $eventPayload['first_name'], $message->getMetadata()));
        }

        if (isset($eventPayload['last_name'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressLastNameCommand($message->getId(), $eventPayload['last_name'], $message->getMetadata()));
        }

        if (isset($eventPayload['company_name'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressCompanyNameCommand($message->getId(), $eventPayload['company_name'], $message->getMetadata()));
        }

        if (isset($eventPayload['address_one'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressLineOneCommand($message->getId(), $eventPayload['address_one'], $message->getMetadata()));
        }

        if (isset($eventPayload['address_two'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressLineTwoCommand($message->getId(), $eventPayload['address_two'], $message->getMetadata()));
        }

        if (isset($eventPayload['state'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressStateCommand($message->getId(), $eventPayload['state'], $message->getMetadata()));
        }

        if (isset($eventPayload['city'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressCityCommand($message->getId(), $eventPayload['city'], $message->getMetadata()));
        }

        if (isset($eventPayload['county'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressCountyCommand($message->getId(), $eventPayload['county'], $message->getMetadata()));
        }

        if (isset($eventPayload['postal_code'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressPostalCodeCommand($message->getId(), $eventPayload['postal_code'], $message->getMetadata()));
        }

        if (isset($eventPayload['country'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressCountryCommand($message->getId(), $eventPayload['country'], $message->getMetadata()));
        }

        if (isset($eventPayload['country_code'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressCountryCodeCommand($message->getId(), $eventPayload['country_code'], $message->getMetadata()));
        }

        if (isset($eventPayload['latitude'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressLatitudeCommand($message->getId(), $eventPayload['latitude'], $message->getMetadata()));
        }

        if (isset($eventPayload['longitude'])) {
            $this->commandBus->dispatch(new UpdateWarehouseAddressLongitudeCommand($message->getId(), $eventPayload['longitude'], $message->getMetadata()));
        }
    }
}
