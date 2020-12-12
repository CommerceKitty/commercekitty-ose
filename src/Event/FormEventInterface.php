<?php declare(strict_types=1);

namespace CommerceKitty\Event;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
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
     * Does this event really need the request object?
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
     * @return boolean
     */
    public function hasForm(): bool;

    /**
     * @param FormInterface $form
     *
     * @return void
     */
    public function setForm(FormInterface $form): void;
}
