<?php declare(strict_types=1);

namespace CommerceKitty\Model;

/**
 */
interface VendorInterface
{
    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @param string $id
     *
     * @throws Exception
     *
     * @return self
     */
    public function setId(string $id);

    /**
     * @return string
     */
    public function getName(): ?string;
}
