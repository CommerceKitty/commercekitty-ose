<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class TopbarMenuSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'menu.topbar' => [
                ['buildMenu', -255],
            ]
        ];
    }

    /**
     * @param GenericEvent $event
     *
     * @return void
     */
    public function buildMenu(GenericEvent $event): void
    {
        $menu = $event->getSubject();

        $menu->addChild('products');
        $menu['products']->addChild('view all', ['route' => 'product_index']);
    }
}
