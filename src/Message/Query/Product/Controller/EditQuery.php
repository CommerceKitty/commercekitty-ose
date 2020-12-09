<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query\Product\Controller;

use CommerceKitty\Message\Query\QueryInterface;
use Symfony\Component\HttpFoundation\Request;

class EditQuery implements QueryInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param string  $id
     * @param Request $request
     */
    public function __construct(string $id, Request $request)
    {
        $this->id      = $id;
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
