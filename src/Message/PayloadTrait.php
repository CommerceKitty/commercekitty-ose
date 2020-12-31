<?php declare(strict_types=1);

namespace CommerceKitty\Message;

trait PayloadTrait
{
    protected $payload = [];

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function setPayload(array $payload)
    {
        if (!empty($this->payload)) {
            throw new \Exception('Cannot set the payload after it contains data.');
        }

        return $this;
    }

    public function has(string $key): bool
    {
        return isset($this->payload[$key]);
    }

    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->payload[$key];
        }
    }
}
