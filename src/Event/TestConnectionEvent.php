<?php declare(strict_types=1);

namespace App\Event;

use App\Model\ChannelInterface;

/**
 * Test Connection Event
 *
 * This event is dispatched to test a connection for a channel.
 */
class TestConnectionEvent
{
    /**
     * @var ChannelInterface
     */
    private $channel;

    /**
     * @var bool
     */
    private $success = false;

    /**
     * @var string
     */
    private $message = '';

    /**
     * @param ChannelInterface $channel
     */
    public function __construct(ChannelInterface $channel)
    {
        $this->channel = $channel;
    }

    /**
     * @return ChannelInterface
     */
    public function getChannel(): ChannelInterface
    {
        return $this->channel;
    }

    /**
     * If the test was successful, this will return true
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     *
     * @return self
     */
    public function setSuccess(bool $success): self
    {
        $this->success = $success;

        return $this;
    }

    /**
     * If the test failed, this MUST be set with the error message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
