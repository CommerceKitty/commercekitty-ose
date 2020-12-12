<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Product;

use CommerceKitty\Message\Query\Product\FindByProductQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use CommerceKitty\Model\ProductInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindByProductQueryHandler implements QueryHandlerInterface
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(FindByProductQuery $message)
    {
        return $this->manager->getRepository(ProductInterface::class)
            ->findBy($message->getCriteria(), $message->getOrderBy(), $message->getLimit(), $message->getOffset());
    }
}
