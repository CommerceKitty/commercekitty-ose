<?php declare(strict_types=1);

namespace App\MessageHandler\Channel\Woocommerce;

use App\Message\Channel\Woocommerce\ImportProductsMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ImportProductsHandler implements MessageHandlerInterface
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
     * @param ImportProductsMessage $message
     *
     * @return void
     */
    public function __invoke(ImportProductsMessage $message): void
    {
        // @todo
    }
}
