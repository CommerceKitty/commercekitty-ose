<?php

namespace CommerceKitty\EventSubscriber\Product;

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
                ['buildMenu', -225],
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

        $menu->addChild('products', [
            'extras' => [
                'icon' => 'fas fa-barcode fa-fw',
            ],
        ]);
        $menu['products']->addChild('new', [
            'route'  => 'product_select_type',
            'extras' => [
                'icon' => 'fas fa-plus fa-fw',
            ],
        ]);
        $menu['products']->addChild('view all', [
            'route'  => 'product_index',
            'extras' => [
                'icon' => 'fas fa-list fa-fw',
            ],
        ]);
    }
}
