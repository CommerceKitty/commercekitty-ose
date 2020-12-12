<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Product;

use CommerceKitty\Message\Query\Product\CountByProductQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use CommerceKitty\Model\ProductInterface;
use Doctrine\ORM\EntityManagerInterface;

class CountByProductQueryHandler implements QueryHandlerInterface
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(CountByProductQuery $message)
    {
        return $this->manager->getRepository(ProductInterface::class)
            ->count($message->getCriteria());
    }
}
