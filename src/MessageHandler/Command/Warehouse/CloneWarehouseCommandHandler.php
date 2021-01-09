<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Warehouse;

use CommerceKitty\Entity\Warehouse\WarehouseEventStore;
use CommerceKitty\HandleTrait;
use CommerceKitty\Message\Command\Warehouse\CloneWarehouseCommand;
use CommerceKitty\Message\Command\Warehouse\CreateWarehouseCommand;
use CommerceKitty\Message\Event\Warehouse\ClonedWarehouseEvent;
use CommerceKitty\Message\Query\Warehouse\FindWarehouseQuery;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Uid\Ulid;

class CloneWarehouseCommandHandler implements CommandHandlerInterface
{
    use HandleTrait;

    private $manager;
    private $eventBus;
    private $commandBus;

    /**
     */
    public function __construct(EntityManagerInterface $manager, MessageBusInterface $commandBus, MessageBusInterface $eventBus, MessageBusInterface $queryBus)
    {
        $this->manager    = $manager;
        $this->eventBus   = $eventBus;
        $this->queryBus   = $queryBus;
        $this->commandBus = $commandBus;
    }

    /**
     * @return void
     */
    public function __invoke(CloneWarehouseCommand $message): void
    {
        $this->manager->clear();

        $model   = $this->handle(new FindWarehouseQuery($message->get('id')));
        $payload = $model->toPayload();

        $payload['id']   = $message->get('clone_id');
        $payload['name'] = 'Clone of: '.$payload['name'];

        $this->commandBus->dispatch(new CreateWarehouseCommand($payload, $message->getMetadata()));
    }
}
