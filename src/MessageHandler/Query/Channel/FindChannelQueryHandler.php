<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Channel;

use CommerceKitty\Message\Query\Channel\FindChannelQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use CommerceKitty\Model\ChannelInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindChannelQueryHandler implements QueryHandlerInterface
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(FindChannelQuery $message)
    {
        return $this->manager->getRepository(ChannelInterface::class)
            ->find($message->getId());
    }
}
