<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Woocommerce;

use CommerceKitty\Message\Command\Woocommerce\ImportProductsCommand;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ImportProductsHandler implements CommandHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @param EntityManagerInterface $manager
     * @param HttpClientInterface    $client
     */
    public function __construct(EntityManagerInterface $manager, HttpClientInterface $client)
    {
        $this->manager = $manager;
        $this->client  = $client;
    }

    /**
     * @param ImportProductsCommand $message
     *
     * @return void
     */
    public function __invoke(ImportProductsCommand $message): void
    {
        // @todo
    }
}
