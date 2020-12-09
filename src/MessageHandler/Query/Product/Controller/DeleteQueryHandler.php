<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Product\Controller;

use CommerceKitty\Model\ProductInterface;
use CommerceKitty\Message\Query\Product\Controller\DeleteQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class DeleteQueryHandler implements QueryHandlerInterface
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
     * @param DeleteQuery $message
     *
     * @return ProductInterface|null
     */
    public function __invoke(DeleteQuery $message): ?ProductInterface
    {
        return $this->manager->getRepository(ProductInterface::class)->find($message->getId());
    }
}
