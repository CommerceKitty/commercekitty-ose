<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query;

trait CountByTrait
{
    protected $criteria;

    public function __construct(array $criteria = [])
    {
        $this->criteria = $criteria;
    }

    public function getCriteria()
    {
        return $this->criteria;
    }
}
