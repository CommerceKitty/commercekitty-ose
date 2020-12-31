<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Warehouse;

use CommerceKitty\Message\Query\Warehouse\CountByWarehouseQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use CommerceKitty\Model\WarehouseInterface;
use Doctrine\ORM\EntityManagerInterface;

class CountByWarehouseQueryHandler implements QueryHandlerInterface
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(CountByWarehouseQuery $message)
    {
        return $this->manager->getRepository(WarehouseInterface::class)
            ->count($message->getCriteria());
    }
}
