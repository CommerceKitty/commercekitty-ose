<?php declare(strict_types=1);

namespace App\Model;

interface DataFeedColumnInterface
{
    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return DataFeedInterface
     */
    public function getDataFeed(): DataFeedInterface;

    /**
     * Position of this field, the higher the number the further down it is
     *
     * Defaults to 0
     *
     * @return integer
     */
    public function getPosition(): int;

    /**
     * @return string
     */
    public function getValue(): ?string;

    /**
     * If there is no value, this is the value that will be used
     *
     * @return string
     */
    public function getDefaultValue(): ?string;

    /**
     * What field is this pulling from? It should follow the naming convention
     * of "{entity}.{field}" for example: "product.id"
     */
    public function getPropertyPath(): ?string;
}
