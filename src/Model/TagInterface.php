<?php declare(strict_types=1);

namespace CommerceKitty\Model;

/**
 */
interface TagInterface
{
    /**
     * @see self::getName()
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getName(): ?string;

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self;
}
