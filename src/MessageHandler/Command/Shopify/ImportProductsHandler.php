<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Shopify;

use CommerceKitty\Message\Command\Shopify\ImportProductsCommand;
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
    private $httpClient;

    /**
     * @param EntityManagerInterface $manager
     * @param HttpClientInterface    $httpClient
     */
    public function __construct(EntityManagerInterface $manager, HttpClientInterface $httpClient)
    {
        $this->manager    = $manager;
        $this->httpClient = $httpClient;
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
