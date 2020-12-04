<?php declare(strict_types=1);

namespace CommerceKitty\Model;

use DateTimeInterface;
use function Symfony\Component\String\u;

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

    /**
     */
    public function getType(): string
    {
        $class = get_class($this);
        $parts = explode('\\', $class);
        $name  = preg_replace('/Channel/', '', array_pop($parts));

        return (string) u($name)->snake();
    }
}
