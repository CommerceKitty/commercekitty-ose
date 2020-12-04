<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command;

use CommerceKitty\Entity\Product;
use CommerceKitty\Message\Command\CreateProductCommand;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CreateProductHandler implements CommandHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param CreateProductsCommand $message
     *
     * @return void
     */
    public function __invoke(CreateProductCommand $message): void
    {
        //> Avoid Dupes
        $entity = $this->manager->getRepository(Product::class)->findOneBySku($message->getSku());
        if ($entity) {
            return;
        }
        //< Avoid Dupes

        $entity = (new Product())
            ->setName($message->getName())
            ->setType($message->getType())
            ->setSku($message->getSku())
        ;
        $this->manager->persist($entity);
        $this->manager->flush();
    }
}
