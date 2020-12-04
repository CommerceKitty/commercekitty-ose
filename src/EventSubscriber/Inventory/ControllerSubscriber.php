<?php

namespace CommerceKitty\EventSubscriber\Inventory;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class ControllerSubscriber implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'response.inventory.deleted' => 'onInventoryDeleted',
        ];
    }

    /**
     * @param GenericEvent $event
     *
     * @return void
     */
    public function onInventoryDeleted(GenericEvent $event): void
    {
        $entity = $event->getSubject();
        $url    = $this->router->generate('product_inventory', ['id' => $entity->getProduct()->getId()]);

        $event->setArgument('response', new RedirectResponse($url));
    }
}
