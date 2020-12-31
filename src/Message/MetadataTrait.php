<?php declare(strict_types=1);

namespace CommerceKitty\Message;

trait MetadataTrait
{
    protected $metadata = [];

    public function getMetadata(string $key = '')
    {
        if (!empty($key)) {
            if ($this->hasMetadata($key)) {
                return $this->metadata[$key];
            }

            return null;
        }

        return $this->metadata;
    }

    public function setMetadata(array $metadata)
    {
        if (!empty($this->metadata)) {
            throw new \Exception('Cannot set metadata after it contains data.');
        }

        return $this;
    }

    public function hasMetadata(string $key): bool
    {
        return isset($this->metadata[$key]);
    }
}
