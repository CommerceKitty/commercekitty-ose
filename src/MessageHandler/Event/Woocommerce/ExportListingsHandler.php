<?php declare(strict_types=1);

namespace App\MessageHandler\Event\Woocommerce;

use App\Message\Event\Woocommerce\ExportListingsMessage;
use App\MessageHandler\Event\EventHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ExportListingsHandler implements EventHandlerInterface
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
     * @param ExportListingsMessage $message
     *
     * @return void
     */
    public function __invoke(ExportListingsMessage $message): void
    {
        // @todo
    }
}
