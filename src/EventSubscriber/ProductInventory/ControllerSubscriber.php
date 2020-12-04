<?php

namespace CommerceKitty\EventSubscriber\ProductInventory;

use CommerceKitty\Entity;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @param RouterInterface        $router
     * @param EntityManagerInterface $manager
     */
    public function __construct(RouterInterface $router, EntityManagerInterface $manager)
    {
        $this->router  = $router;
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'controller.product_inventory.select_warehouse.initialize' => [
                ['checkWarehouseCount'],
                ['checkForSingleWarehouse'],
            ],
            'controller.product_inventory.new.initialize' => 'checkForDupes',
        ];
    }

    /**
     * @param GenericEvent $event
     *
     * @return void
     */
    public function checkWarehouseCount(GenericEvent $event): void
    {
        $entity  = $event->getSubject(); // @var ProductInterface
        $request = $event->getArgument('request');
        $count   = $this->manager->getRepository(Entity\Warehouse::class)->count([]);

        if ($count >= 1) {
            return;
        }

        $request->getSession()->getFlashBag()->set('warning', 'Must have at least one warehouse create.');

        $url = $this->router->generate('product_inventory', ['id' => $entity->getId()]);

        $event->setArgument('response', new RedirectResponse($url));
    }

    /**
     * @param GenericEvent $event
     *
     * @return void
     */
    public function checkForSingleWarehouse(GenericEvent $event): void
    {
        $entity              = $event->getSubject(); // @var ProductInterface
        $request             = $event->getArgument('request');
        $warehouseCollection = $this->manager->getRepository(Entity\Warehouse::class)->findAll();
        $count               = count($warehouseCollection);

        if (1 == $count) {
            $request->getSession()->set('warehouse[id]', $warehouseCollection[0]->getId());

            $url = $this->router->generate('product_inventory_new', ['id' => $entity->getId()]);

            $event->setArgument('response', new RedirectResponse($url));
        }
    }

    /**
     * @param GenericEvent $event
     *
     * @return void
     */
    public function checkForDupes(GenericEvent $event): void
    {
        $entity    = $event->getSubject(); // @var ProductInterface
        $request   = $event->getArgument('request');
        $warehouse = $event->getArgument('warehouse');

        $inventory = $this->manager->getRepository(Entity\Inventory::class)->findOneBy([
            'product'   => $entity,
            'warehouse' => $warehouse,
        ]);

        if (null === $inventory) {
            return;
        }

        $request->getSession()->getFlashBag()->set('warning', 'Inventory record already exists for this product and warehouse.');

        $url = $this->router->generate('product_inventory_select_warehouse', ['id' => $entity->getId()]);

        $event->setArgument('response', new RedirectResponse($url));
    }
}
