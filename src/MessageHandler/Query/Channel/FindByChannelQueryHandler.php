<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Channel;

use CommerceKitty\Message\Query\Channel\FindByChannelQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use CommerceKitty\Model\ChannelInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindByChannelQueryHandler implements QueryHandlerInterface
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(FindByChannelQuery $message)
    {
        return $this->manager->getRepository(ChannelInterface::class)
            ->findBy($message->getCriteria(), $message->getOrderBy(), $message->getLimit(), $message->getOffset());
    }
}
