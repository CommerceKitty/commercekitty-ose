<?php declare(strict_types=1);

namespace CommerceKitty\Event;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

interface QueryBuilderEventInterface
{
    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder;

    /**
     */
    public function setQueryBuilder(QueryBuilder $queryBuilder);

    /**
     */
    public function getRequest(): Request;
}
