<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Product;

use CommerceKitty\Message\Query\Product\FindProductQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use CommerceKitty\Model\ProductInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindProductQueryHandler implements QueryHandlerInterface
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(FindProductQuery $message)
    {
        return $this->manager->getRepository(ProductInterface::class)
            ->find($message->getId());
    }
}
