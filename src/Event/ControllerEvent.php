<?php declare(strict_types=1);

namespace CommerceKitty\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class ControllerEvent extends Event implements ControllerEventInterface
{
    /**
     * @var object
     */
    protected $entity;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param object  $entity
     * @param Request $request
     */
    public function __construct($entity, Request $request)
    {
        $this->entity  = $entity;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    /**
     * {@inheritdoc}
     */
    public function hasResponse(): bool
    {
        return (null !== $this->response);
    }
}
