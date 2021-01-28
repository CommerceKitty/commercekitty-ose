<?php declare(strict_types=1);

namespace CommerceKitty\Event;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class QueryBuilderEvent implements QueryBuilderEventInterface
{
    private $queryBuilder;
    private $request;

    /**
     */
    public function __construct(Request $request, QueryBuilder $queryBuilder)
    {
        $this->request      = $request;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function setQueryBuilder(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
