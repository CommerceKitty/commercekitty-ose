<?php declare(strict_types=1);

namespace App\Model;

use DateTimeInterface;

/**
 */
abstract class AbstractChannel implements ChannelInterface
{
    protected $id;
    protected $name;
    protected $enabled;

    /**
     */
    public function __construct()
    {
        $this->enabled = true;
    }

    /**
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
