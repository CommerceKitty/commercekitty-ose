<?php declare(strict_types=1);

namespace CommerceKitty\Model;

use DateTimeInterface;

/**
 */
interface ChannelInterface
{
    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * Returns the name of this channel set by the user
     *
     * @return string
     */
    public function getName(): ?string;

    /**
     * Returns true if we should sync this channel
     *
     * @return boolean
     */
    public function isEnabled(): bool;
}
