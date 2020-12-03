<?php declare(strict_types=1);

namespace App\Message;

trait ChannelAwareTrait
{
    /**
     * @var string
     */
    protected $channelId;

    /**
     * @return string
     */
    public function getChannelId(): string
    {
        return (string) $this->channelId;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function setChannelId(string $id): self
    {
        $this->channelId = $id;

        return $this;
    }
}
