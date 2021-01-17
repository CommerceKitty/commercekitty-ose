<?php declare(strict_types=1);

namespace CommerceKitty\Command;

use CommerceKitty\Entity\Warehouse\WarehouseEventStore;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ReplayWarehouseEventsCommand extends Command
{
    protected static $defaultName = 'app:warehouse:replay';

    private $manager;
    private $warehouse;

    public function __construct(EntityManagerInterface $manager, MessageBusInterface $eventBus)
    {
        $this->manager  = $manager;
        $this->eventBus = $eventBus;

        parent::__construct();
    }

    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Count Events

        $collection = $this->manager->getRepository(WarehouseEventStore::class)
            ->createQueryBuilder('es')
            ->orderBy('es.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        $namespace = 'CommerceKitty\\Message\\Event\\Warehouse';
        foreach ($collection as $event) {
            $fqcn    = $namespace.'\\'.$event->getEventType().'Event';
            $command = new $fqcn($event->getPayload(), $event->getMetadata());

            $this->eventBus->dispatch($command);
        }

        return Command::SUCCESS;
    }
}
