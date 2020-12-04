<?php

namespace CommerceKitty\EventSubscriber;

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
                ['buildMenu', -999],
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

        $menu->addChild('more', [
            'extras' => [
                //'icon' => 'fas fa-caret-down fa-fw',
            ],
        ]);
        $menu['more']->addChild('tags', [
            //'route'  => 'tag_index',
            'extras' => [
                'icon' => 'fas fa-tags fa-fw',
            ],
        ]);
        $menu['more']->addChild('users', [
            //'route'  => 'user_index',
            'extras' => [
                'icon' => 'fas fa-users fa-fw',
            ],
        ]);
        $menu['more']->addChild('app settings', [
            //'route'  => 'app_setting_index',
            'extras' => [
                'icon' => 'fas fa-cogs fa-fw',
            ],
        ]);
        $menu['more']->addChild('data feeds', [
            //'route'  => 'data_feed_index',
            'extras' => [
                'icon' => 'fas fa-rss fa-fw',
            ],
        ]);
        $menu['more']->addChild('integrations', [
            //'route'  => 'integration_index',
            'extras' => [
                'icon' => 'fas fa-plug fa-fw',
            ],
        ]);
    }
}
