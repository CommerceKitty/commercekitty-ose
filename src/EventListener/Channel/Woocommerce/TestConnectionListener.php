<?php declare(strict_types=1);

namespace CommerceKitty\EventListener\Channel\Woocommerce;

use CommerceKitty\Event\TestConnectionEvent;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @todo Make Subscriber
 */
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
     * @param TestConnectionEvent $event
     *
     * @return void
     */
    public function onChannelWoocommerceTestConnection(TestConnectionEvent $event): void
    {
        try {
            // @todo use Consumer Key & Consumer Secret
            $response = $this->client->request('GET', $event->getChannel()->getHost());

            $event->setSuccess(200 === $response->getStatusCode());
        } catch (TransportExceptionInterface $e) {
            $response->cancel();
            $event
                ->setSuccess(false)
                ->setMessage($e->getMessage())
            ;
        }
    }
}
