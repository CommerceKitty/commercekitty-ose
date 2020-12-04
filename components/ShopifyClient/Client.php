<?php declare(strict_types=1);

namespace CommerceKitty\Component\ShopifyClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Client
{
    /**
     */
    const API_VERSION = '2020-10';

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    protected $host;
    protected $apiKey;
    protected $password;

    /**
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->httpClient = $client;
    }

    public function withHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function withApiKey(string $key): self
    {
        $this->apiKey = $key;

        return $this;
    }

    public function withPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * ie ->getResource('GET', '/shop.json');
     */
    public function getResource(string $method = 'GET', string $uri): ResponseInterface
    {
        return $this->httpClient->request('GET', $this->host.'/admin/api/'.self::API_VERSION.$uri, [
            'auth_basic' => [$this->apiKey, $this->password],
        ]);
    }

    /**
     */
    public function getShopResponse(): ResponseInterface
    {
        return $this->getResource('GET', '/shop.json');
    }
}
