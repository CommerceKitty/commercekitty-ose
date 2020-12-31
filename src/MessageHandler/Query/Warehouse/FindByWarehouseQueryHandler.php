<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Warehouse;

use CommerceKitty\Message\Query\Warehouse\FindByWarehouseQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use CommerceKitty\Model\WarehouseInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindByWarehouseQueryHandler implements QueryHandlerInterface
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(FindByWarehouseQuery $message)
    {
        return $this->manager->getRepository(WarehouseInterface::class)
            ->findBy($message->getCriteria(), $message->getOrderBy(), $message->getLimit(), $message->getOffset());
    }
}
