<?php

namespace App\EventSubscriber\Channel;

use App\Event\TestConnectionEvent;
use CommerceKitty\Component\ShopifyClient\Client;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ShopifySubscriber implements EventSubscriberInterface
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
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
        $client = (new Client($this->httpClient))
            ->withHost($event->getChannel()->getHost())
            ->withApiKey($event->getChannel()->getApiKey())
            ->withPassword($event->getChannel()->getPassword())
        ;

        try {
            $response = $client->getShopResponse();

            $event->setSuccess(200 === $response->getStatusCode());
        } catch (TransportExceptionInterface $e) {
            $event
                ->setSuccess(false)
                ->setMessage($e->getMessage())
            ;
        } catch (ClientException $e) {
            $event
                ->setSuccess(false)
                ->setMessage($e->getMessage())
            ;
        }
    }
}
