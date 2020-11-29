<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfileMenuSubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'menu.profile' => [
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
        $menu     = $event->getSubject();
        $username = $this->tokenStorage->getToken()->getUser()->__toString();

        $menu->addChild($username);

        $menu[$username]->addChild('edit profile', [
            'route'  => 'profile_edit',
            'extras' => [
                'icon' => 'fas fa-edit fa-fw',
            ],
        ]);
        $menu[$username]->addChild('change password', [
            'route'  => 'change_password',
            'extras' => [
                'icon' => 'fas fa-key fa-fw',
            ],
        ]);
        $menu[$username]->addChild('settings', [
            //'route'  => 'settings',
            'extras' => [
                'icon' => 'fas fa-cogs fa-fw',
            ],
        ]);
        $menu[$username]->addChild('logout', [
            'route'  => 'logout',
            'extras' => [
                'icon' => 'fas fa-sign-out-alt fa-fw',
            ],
        ]);
    }
}
