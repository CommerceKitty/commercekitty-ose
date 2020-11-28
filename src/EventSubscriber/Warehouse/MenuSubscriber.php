<?php

namespace App\EventSubscriber\Warehouse;

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

        $menu->addChild('warehouses', [
            'extras' => [
                'icon' => 'fas fa-warehouse fa-fw',
            ],
        ]);
        $menu['warehouses']->addChild('add', [
            'route'  => 'warehouse_new',
            'extras' => [
                'icon' => 'fas fa-plus fa-fw',
            ],
        ]);
        $menu['warehouses']->addChild('view all', [
            'route'  => 'warehouse_index',
            'extras' => [
                'icon' => 'fas fa-list fa-fw',
            ],
        ]);
    }
}
