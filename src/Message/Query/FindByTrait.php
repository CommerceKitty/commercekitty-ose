<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query;

trait FindByTrait
{
    protected $criteria;
    protected $orderBy;
    protected $limit;
    protected $offset;

    public function __construct(array $criteria = [], array $orderBy = null, $limit = null, $offset = null)
    {
        $this->criteria = $criteria;
        $this->orderBy  = $orderBy;
        $this->limit    = $limit;
        $this->offset   = $offset;
    }

    public function getCriteria()
    {
        return $this->criteria;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getOffset()
    {
        return $this->offset;
    }
}
