<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query;

trait FindOneByTrait
{
    protected $criteria;
    protected $orderBy;

    public function __construct(array $criteria, array $orderBy = null)
    {
        $this->criteria = $criteria;
        $this->orderBy  = $orderBy;
    }

    public function getCriteria()
    {
        return $this->criteria;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }
}
