<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param FactoryInterface         $factory
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(FactoryInterface $factory, EventDispatcherInterface $dispatcher)
    {
        $this->factory    = $factory;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param array $options
     *
     * @return ItemInterface
     */
    public function buildGenericMenu(array $options): ItemInterface
    {
        if (empty($options['event_name'])) {
            throw new \Exception('Must include "event_name" option.');
        }

        $menu  = $this->factory->createItem('root');
        $event = $this->dispatcher->dispatch(new GenericEvent($menu, $options), 'menu.'.$options['event_name']);

        return $menu;
    }
}
