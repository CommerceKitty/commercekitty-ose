<?php

namespace CommerceKitty\EventSubscriber\Vendor;

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

        $menu->addChild('vendors', [
            'extras' => [
                'icon' => 'fas fa-truck fa-fw',
            ],
        ]);
        $menu['vendors']->addChild('add', [
            'route'  => 'vendor_new',
            'extras' => [
                'icon' => 'fas fa-plus fa-fw',
            ],
        ]);
        $menu['vendors']->addChild('view all', [
            'route'  => 'vendor_index',
            'extras' => [
                'icon' => 'fas fa-list fa-fw',
            ],
        ]);
    }
}
