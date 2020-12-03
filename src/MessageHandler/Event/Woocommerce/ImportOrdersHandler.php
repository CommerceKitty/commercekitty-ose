<?php declare(strict_types=1);

namespace App\MessageHandler\Event\Woocommerce;

use App\Message\Event\Woocommerce\ImportOrdersMessage;
use App\MessageHandler\Event\EventHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ImportOrdersHandler implements EventHandlerInterface
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
     * @param ImportOrdersMessage $message
     *
     * @return void
     */
    public function __invoke(ImportOrdersMessage $message): void
    {
        // @todo
    }
}
