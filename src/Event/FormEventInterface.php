<?php declare(strict_types=1);

namespace App\Event;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @todo
 */
interface FormEventInterface
{
    /**
     * Returns Entity Object this for should be for
     *
     * @return object
     */
    public function getEntity();

    /**
     * Returns the Request Object
     *
     * @return Request
     */
    public function getRequest(): Request;

    /**
     * Returns the Form that needs to be used
     *
     * @return FormInterface
     */
    public function getForm(): ?FormInterface;

    /**
     * @return Response|null
     */
    public function getResponse(): ?Response;
}
