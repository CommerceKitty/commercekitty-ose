<?php

namespace App\EventSubscriber\Channel;

use App\Event\TestConnectionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ShopifySubscriber implements EventSubscriberInterface
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
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'channel.shopify.test_connection' => 'onChannelShopifyTestConnection',
        ];
    }

    /**
     * @param TestConnectionEvent $event
     *
     * @return void
     */
    public function onChannelShopifyTestConnection(TestConnectionEvent $event): void
    {
        try {
            // @todo Make API call for shop info
            $response = $this->client->request('GET', $event->getChannel()->getHost(), [
                'auth_basic' => [$event->getChannel()->getApiKey(), $event->getChannel()->getPassword()],
            ]);

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
