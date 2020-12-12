<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\User;

use CommerceKitty\Message\Command\User\ChangePasswordCommand;
use CommerceKitty\Message\Query\User\FindUserQuery;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChangePasswordCommandHandler implements CommandHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @param EntityManagerInterface       $manager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param MessageBusInterface          $queryBus
     */
    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder, MessageBusInterface $queryBus)
    {
        $this->manager         = $manager;
        $this->passwordEncoder = $passwordEncoder;
        $this->queryBus        = $queryBus;
    }

    /**
     * @return void
     */
    public function __invoke(ChangePasswordCommand $message): void
    {
        $this->manager->clear();

        $entity = $this->queryBus->dispatch(new FindUserQuery($message->get('user_id')))->last(HandledStamp::class)->getResult();
        if (null === $entity) {
            throw new UnrecoverableMessageHandlingException();
        }

        $password = $this->passwordEncoder->encodePassword($entity, $message->get('plain_password'));
        $entity->setPassword($password);

        $this->manager->persist($entity);
        $this->manager->flush();
    }
}
