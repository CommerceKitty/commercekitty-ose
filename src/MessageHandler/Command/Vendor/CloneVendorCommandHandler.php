<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Vendor;

use CommerceKitty\HandleTrait;
use CommerceKitty\Message\Command\Vendor\CloneVendorCommand;
use CommerceKitty\Message\Command\Vendor\CreateVendorCommand;
use CommerceKitty\Message\Query\Vendor\FindVendorQuery;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CloneVendorCommandHandler implements CommandHandlerInterface
{
    use HandleTrait;

    private $manager;
    private $commandBus;

    /**
     */
    public function __construct(EntityManagerInterface $manager, MessageBusInterface $commandBus, MessageBusInterface $queryBus)
    {
        $this->manager    = $manager;
        $this->queryBus   = $queryBus;
        $this->commandBus = $commandBus;
    }

    /**
     * @return void
     */
    public function __invoke(CloneVendorCommand $message): void
    {
        $this->manager->clear();

        $model   = $this->handle(new FindVendorQuery($message->get('id')));
        $payload = $model->toPayload();

        $payload['id']   = $message->get('clone_id');
        $payload['name'] = 'Clone of: '.$payload['name'];

        $this->commandBus->dispatch(new CreateVendorCommand($payload, $message->getMetadata()));
    }
}
