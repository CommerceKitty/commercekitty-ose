<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Vendor;

use CommerceKitty\Entity\Vendor\VendorEventStore;
use CommerceKitty\Message\Query\Vendor\FindVendorQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use CommerceKitty\Model\Vendor;
use CommerceKitty\Model\VendorInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindVendorQueryHandler implements QueryHandlerInterface
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
    public function __invoke(FindVendorQuery $message)
    {
        $eventCollection = $this->manager->getRepository(VendorEventStore::class)
            ->createQueryBuilder('e')
            ->where('e.aggregateRootId = :aggregateRootId')
            ->orderBy('e.createdAt', 'ASC')
            ->setParameters([
                'aggregateRootId' => $message->getId(),
            ])
            ->getQuery()
            ->getResult()
        ;

        if (0 === count($eventCollection)) {
            return null;
        }

        $model = new Vendor();
        foreach ($eventCollection as $event) {
            $model->apply($event);
        }

        return $model;
    }
}
