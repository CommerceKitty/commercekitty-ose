<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query\Product\Controller;

use CommerceKitty\Message\Query\QueryInterface;
use Symfony\Component\HttpFoundation\Request;

class IndexQuery implements QueryInterface
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
