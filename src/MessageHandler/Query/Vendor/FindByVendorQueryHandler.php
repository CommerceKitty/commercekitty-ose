<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Vendor;

use CommerceKitty\Message\Query\Vendor\FindByVendorQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use CommerceKitty\Model\VendorInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindByVendorQueryHandler implements QueryHandlerInterface
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(FindByVendorQuery $message)
    {
        return $this->manager->getRepository(VendorInterface::class)
            ->findBy($message->getCriteria(), $message->getOrderBy(), $message->getLimit(), $message->getOffset());
    }
}
