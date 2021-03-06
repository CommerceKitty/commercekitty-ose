<?php declare(strict_types=1);

namespace CommerceKitty\Model;

trait WoocommerceChannelTrait
{
    protected $host;
    protected $consumerKey;
    protected $consumerSecret;

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
    public function getConsumerKey(): ?string
    {
        return $this->consumerKey;
    }

    /**
     */
    public function setConsumerKey(string $key): self
    {
        $this->consumerKey = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getConsumerSecret(): ?string
    {
        return $this->consumerSecret;
    }

    /**
     */
    public function setConsumerSecret(string $secret): self
    {
        $this->consumerSecret = $secret;

        return $this;
    }

    public function getPayload(): array
    {
        $payload = parent::getPayload();

        $payload['host']            = $this->host;
        $payload['consumer_key']    = $this->consumerKey;
        $payload['consumer_secret'] = $this->consumerSecret;

        return $payload;
    }
}
