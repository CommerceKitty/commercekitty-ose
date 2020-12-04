<?php

namespace CommerceKitty\EventSubscriber\Channel;

use CommerceKitty\Event\TestConnectionEvent;
use CommerceKitty\Component\ShopifyClient\Client;
use Psr\Log\LoggerInterface;
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

    private $logger;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->logger     = $logger;
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
            $event->setSuccess(200 === $client->getShopResponse()->getStatusCode());
            return;
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

        $this->logger->error('[{channel_id}:{channel_type}] Channel failed to make a successful connection.', [
            'channel_id'   => $event->getChannel()->getId(),
            'channel_type' => $event->getChannel()->getType(),
            'channel_host' => $event->getChannel()->getHost(),
            '__CLASS__'    => __CLASS__,
            '__METHOD__'   => __METHOD__,
        ]);
    }
}
