<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Query\User;

use CommerceKitty\Message\Query\User\FindUserQuery;
use CommerceKitty\MessageHandler\Query\QueryHandlerInterface;
use CommerceKitty\Model\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindUserQueryHandler implements QueryHandlerInterface
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(FindUserQuery $message)
    {
        return $this->manager->getRepository(UserInterface::class)
            ->find($message->getId());
    }
}
