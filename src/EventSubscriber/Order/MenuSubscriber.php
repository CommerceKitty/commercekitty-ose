<?php

namespace App\EventSubscriber\Order;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class MenuSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'menu.topbar' => [
                ['buildMenu', -245],
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

        $menu->addChild('orders', [
            'extras' => [
                'icon' => 'fas fa-cash-register fa-fw',
            ],
        ]);
        $menu['orders']->addChild('view all', [
            //'route'  => 'product_index',
            'extras' => [
                'icon' => 'fas fa-list fa-fw',
            ],
        ]);
    }
}
