<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\Channel;

use CommerceKitty\Message\Query\Channel\CountByChannelQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use CommerceKitty\Model\ChannelInterface;
use Doctrine\ORM\EntityManagerInterface;

class CountByChannelQueryHandler implements QueryHandlerInterface
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(CountByChannelQuery $message)
    {
        return $this->manager->getRepository(ChannelInterface::class)
            ->count($message->getCriteria());
    }
}
