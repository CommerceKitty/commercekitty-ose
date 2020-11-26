<?php declare(strict_types=1);

namespace App\Model;

/**
 */
interface TaggableInterface
{
    /**
     * @return TagInterface[]
     */
    public function getTags();

    /**
     * @param TagInterface $tag
     *
     * @return self
     */
    public function addTag(TagInterface $tag): self;
}
