<?php declare(strict_types=1);

namespace App\EventListener\Channel\Woocommerce;

use App\Event\TestConnectionEvent;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TestConnectionListener
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param TestChannelEvent $event
     *
     * @return void
     */
    public function onChannelWoocommerceTestConnection(TestConnectionEvent $event): void
    {
        try {
            // @todo use Consumer Key & Consumer Secret
            $response = $this->client->request('GET', $event->getChannel()->getHost());

            // This is here because if not, it will check on __destruct and
            // exception will not be caught
            $response->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            $response->cancel();
            $event
                ->setSuccess(false)
                ->setMessage($e->getMessage())
            ;

            return;
        }

        $event->setSuccess(true);
    }
}
