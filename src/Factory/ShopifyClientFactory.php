<?php declare(strict_types=1);

namespace CommerceKitty\Factory;

use CommerceKitty\Component\ShopifyClient\Client;
use CommerceKitty\Model\ShopifyChannelInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ShopifyClientFactory
{
    /**
     * @var HttpClientInerface
     */
    protected $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param ShopifyChannelInterface $channel
     *
     * @return Client
     */
    public function createShopifyClient(ShopifyChannelInterface $channel)
    {
        return (new Client($this->httpClient))
            ->withHost($channel->getHost())
            ->withApiKey($channel->getApiKey())
            ->withPassword($channel->getPassword())
        ;
    }
}
