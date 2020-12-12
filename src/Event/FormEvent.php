<?php declare(strict_types=1);

namespace CommerceKitty\Event;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 */
class FormEvent implements FormEventInterface
{
    protected $entity;
    protected $request;
    protected $form;

    public function __construct($entity, $request)
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
    public function getForm(): ?FormInterface
    {
        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    public function hasForm(): bool
    {
        return (null !== $this->form);
    }

    /**
     * {@inheritdoc}
     */
    public function setForm(FormInterface $form): void
    {
        $this->form = $form;
    }
}
