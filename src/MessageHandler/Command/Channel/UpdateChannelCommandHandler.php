<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Channel;

use CommerceKitty\Message\Command\Channel\UpdateChannelCommand;
use CommerceKitty\Message\Query\Channel\FindChannelQuery;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UpdateChannelCommandHandler implements CommandHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    private $queryBus;
    private $propertyAccess;

    /**
     */
    public function __construct(EntityManagerInterface $manager, MessageBusInterface $queryBus)
    {
        $this->manager        = $manager;
        $this->queryBus       = $queryBus;
        $this->propertyAccess = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @return void
     */
    public function __invoke(UpdateChannelCommand $message): void
    {
        $this->manager->clear();

        $entity = $this->queryBus->dispatch(new FindChannelQuery($message->get('id')))->last(HandledStamp::class)->getResult();
        if (null === $entity) {
            throw new UnrecoverableMessageHandlingException();
        }

        $payload = $message->getPayload();
        foreach ($payload as $key => $newValue) {
            $currentValue = $this->propertyAccess->getValue($entity, $key);
            if ($currentValue === $newValue) { continue; }

            // Can use this to create another payload that ONLY contains the
            // values that have changed, $eventPayload
            $this->propertyAccess->setValue($entity, $key, $newValue);
        }

        $this->manager->persist($entity);
        $this->manager->flush();

        // @todo Store changes and dispatch event to eventBus
    }
}
