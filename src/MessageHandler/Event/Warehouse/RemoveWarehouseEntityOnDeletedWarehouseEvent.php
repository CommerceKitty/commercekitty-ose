<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Event\Warehouse;

use CommerceKitty\Entity\Warehouse;
use CommerceKitty\HandleTrait;
use CommerceKitty\Message\Event\Warehouse\DeletedEvent;
use CommerceKitty\Message\Query\Warehouse\FindWarehouseQuery;
use CommerceKitty\MessageHandler\Event\EventHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Uid\Ulid;

class RemoveWarehouseEntityOnDeletedWarehouseEvent implements EventHandlerInterface
{
    use HandleTrait;

    private $manager;

    /**
     */
    public function __construct(EntityManagerInterface $manager, MessageBusInterface $queryBus)
    {
        $this->manager  = $manager;
        $this->queryBus = $queryBus;
    }

    /**
     * @return void
     */
    public function __invoke(DeletedEvent $message): void
    {
        $this->manager->clear();

        $model  = $this->handle(new FindWarehouseQuery($message->get('id')));
        $entity = $this->manager->getRepository(Warehouse::class)->find($message->get('id'));

        $this->manager->remove($entity);
        $this->manager->flush();
    }
}
