<?php

namespace App\EventSubscriber\Customer;

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
                ['buildMenu', -235],
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

        $menu->addChild('customers', [
            'extras' => [
                'icon' => 'fas fa-address-book fa-fw',
            ],
        ]);
        $menu['customers']->addChild('new', [
            //'route'  => 'product_select_type',
            'extras' => [
                'icon' => 'fas fa-plus fa-fw',
            ],
        ]);
        $menu['customers']->addChild('view all', [
            //'route'  => 'product_index',
            'extras' => [
                'icon' => 'fas fa-list fa-fw',
            ],
        ]);
    }
}
