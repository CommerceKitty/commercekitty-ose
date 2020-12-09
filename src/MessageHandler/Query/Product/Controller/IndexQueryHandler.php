<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Product\Controller;

use CommerceKitty\Entity\Product;
use CommerceKitty\Message\Query\Product\Controller\IndexQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class IndexQueryHandler implements QueryHandlerInterface
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
     * @param IndexQuery $message
     */
    public function __invoke(IndexQuery $message)
    {
        return $this->manager->getRepository(Product::class)->createQueryBuilder('e');
    }
}
