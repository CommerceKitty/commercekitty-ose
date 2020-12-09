<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Product\Controller;

use CommerceKitty\Entity\Product;
use CommerceKitty\Model\ProductInterface;
use CommerceKitty\Message\Query\Product\Controller\ShowQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ShowQueryHandler implements QueryHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @param EntityManagerInterface
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param ShowQuery $message
     *
     * @return ProductInterface|null
     */
    public function __invoke(ShowQuery $message): ?ProductInterface
    {
        return $this->manager->getRepository(Product::class)->find($message->getId());
    }
}
