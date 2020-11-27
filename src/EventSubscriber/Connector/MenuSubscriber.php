<?php

namespace App\EventSubscriber\Connector;

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

        $menu->addChild('connectors', [
            'extras' => [
                'icon' => 'fas fa-store fa-fw',
            ],
        ]);
        $menu['connectors']->addChild('add', [
            //'route'  => 'connector_new',
            'extras' => [
                'icon' => 'fas fa-plus fa-fw',
            ],
        ]);
        $menu['connectors']->addChild('view all', [
            //'route'  => 'connector_index',
            'extras' => [
                'icon' => 'fas fa-list fa-fw',
            ],
        ]);
    }
}
