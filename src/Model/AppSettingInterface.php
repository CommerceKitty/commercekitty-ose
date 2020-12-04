<?php declare(strict_types=1);

namespace CommerceKitty\Model;

interface AppSettingInterface
{
    /**
     * @return string
     */
    public function getAlias(): ?string;

    /**
     * @return string
     */
    public function getValue(): ?string;

    /**
     * @return bool
     */
    public function getBoolValue(): ?bool;
}
