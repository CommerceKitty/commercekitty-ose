<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Warehouse;

use CommerceKitty\Entity\Warehouse\WarehouseEventStore;
use CommerceKitty\Message\Query\Warehouse\FindWarehouseQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use CommerceKitty\Model\Warehouse;
use CommerceKitty\Model\WarehouseInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindWarehouseQueryHandler implements QueryHandlerInterface
{
    protected $manager;

    /**
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     */
    public function __invoke(FindWarehouseQuery $message)
    {
        $eventCollection = $this->manager->getRepository(WarehouseEventStore::class)
            ->createQueryBuilder('e')
            ->where('e.aggregateRootId = :aggregateRootId')
            ->orderBy('e.createdAt', 'DESC')
            ->setParameters([
                'aggregateRootId' => $message->getId(),
            ])
            ->getQuery()
            ->getResult()
        ;

        if (0 === count($eventCollection)) {
            return null;
        }

        $model = new Warehouse();
        foreach ($eventCollection as $event) {
            $model->apply($event);
        }

        return $model;
    }
}
