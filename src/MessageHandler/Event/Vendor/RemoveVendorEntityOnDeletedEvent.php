<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Event\Vendor;

use CommerceKitty\Entity\Vendor;
use CommerceKitty\HandleTrait;
use CommerceKitty\Message\Event\Vendor\DeletedEvent;
use CommerceKitty\Message\Query\Vendor\FindVendorQuery;
use CommerceKitty\MessageHandler\Event\EventHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

class RemoveVendorEntityOnDeletedEvent implements EventHandlerInterface
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

        $model  = $this->handle(new FindVendorQuery($message->get('id')));
        $entity = $this->manager->getRepository(Vendor::class)->find($message->get('id'));

        if (null === $model || null === $entity) {
            throw new UnrecoverableMessageHandlingException('Vendor Not Found');
        }

        $this->manager->remove($entity);
        $this->manager->flush();
    }
}
