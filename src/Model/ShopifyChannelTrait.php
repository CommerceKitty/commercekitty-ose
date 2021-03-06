<?php declare(strict_types=1);

namespace CommerceKitty\Model;

trait ShopifyChannelTrait
{
    protected $host;
    protected $apiKey;
    protected $password;

    /**
     * @return string
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return self
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     */
    public function setApiKey(string $key): self
    {
        $this->apiKey = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPayload(): array
    {
        $payload = parent::getPayload();

        $payload['host']     = $this->host;
        $payload['api_key']  = $this->apiKey;
        $payload['password'] = $this->password;

        return $payload;
    }
}
