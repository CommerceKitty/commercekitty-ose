<?php declare(strict_types=1);

namespace CommerceKitty\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ControllerEventInterface
{
    /**
     * Returns the Entity that this controller event if for. If this returns
     * `null`, it means there is not an Entity for this event. This will happen
     * for "index", "purge", etc. actions since it's not a specific entity.
     *
     * @return object|null
     */
    public function getEntity();

    /**
     * Returns the Request being used
     *
     * @return Request
     */
    public function getRequest(): Request;

    /**
     * If an event listener/subscriber sets a Response, it will be returned
     *
     * @return Response|null
     */
    public function getResponse(): ?Response;

    /**
     * Set the response that will be returned
     *
     * @param Response $response
     *
     * @return void
     */
    public function setResponse(Response $response): void;

    /**
     * @return boolean
     */
    public function hasResponse(): bool;
}
