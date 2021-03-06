<?php declare(strict_types=1);

namespace CommerceKitty\Message;

use App\Model\ChannelInterface;

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

    /**
     * @param ChannelInterface $channel
     *
     * @return self
     */
    public function setChannel(ChannelInterface $channel): self
    {
        $this->channelId = $channel->getId();

        return $this;
    }
}
