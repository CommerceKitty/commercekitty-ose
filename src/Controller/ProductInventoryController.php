<?php

namespace CommerceKitty\Controller;

use CommerceKitty\Entity;
use CommerceKitty\Form\Type\WarehouseEntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductInventoryController extends AbstractController
{
    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     * @param string                   $id
     *
     * @return Response
     */
    public function selectWarehouse(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entity = $this->getDoctrine()->getRepository(Entity\Product::class)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.product.404', [
                '%entity_class_name%'      => 'Product',
                '%entity_full_class_name%' => Entity\Product::class,
                '%id%'                     => $id,
            ]));
        }

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.product_inventory.select_warehouse.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $form = $this->createForm(WarehouseEntityType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $request->getSession()->set('warehouse[id]', $form->getData()->getId());

            return $this->redirectToRoute('product_inventory_new', ['id' => $entity->getId()]);
        }

        return $this->render('product_inventory/select_warehouse.'.$request->getRequestFormat().'.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     * @param string                   $id
     *
     * @return Response
     */
    public function new(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        if (!$request->getSession()->has('warehouse[id]')) {
            $this->addFlash('warning', 'Invalid Request');

            return $this->redirectToRoute('product_inventory_select_warehouse', ['id' => $id]);
        }

        $entity = $this->getDoctrine()->getRepository(Entity\Product::class)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.product.404', [
                '%entity_class_name%'      => 'Product',
                '%entity_full_class_name%' => Entity\Product::class,
                '%id%'                     => $id,
            ]));
        }

        $warehouse = $this->getDoctrine()->getRepository(Entity\Warehouse::class)->find($request->getSession()->get('warehouse[id]'));
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.warehouse.404', [
                '%entity_class_name%'      => 'Warehouse',
                '%entity_full_class_name%' => Entity\Warehouse::class,
                '%id%'                     => $request->getSession()->get('warehouse[id]'),
            ]));
        }

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request, 'warehouse' => $warehouse]), 'controller.product_inventory.new.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $form = $this->createForm(IntegerType::class, null, [
            'label'              => 'quantity',
            'translation_domain' => 'forms'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager   = $this->getDoctrine()->getManager();
            $inventory = (new Entity\Inventory())
                ->setProduct($entity)
                ->setWarehouse($warehouse)
                ->setQuantity($form->getData())
            ;
            $manager->persist($inventory);
            $manager->flush();

            $request->getSession()->remove('warehouse[id]');

            $this->addFlash('success', $translator->trans('flashes.inventory.created.success', [
                '%entity_class_name%'      => 'Inventory',
                '%entity_full_class_name%' => Entity\Inventory::class,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            return $this->redirectToRoute('product_inventory', ['id' => $entity->getId()]);
        }

        return $this->render('product_inventory/new.html.twig', [
            'entity'    => $entity,
            'form'      => $form->createView(),
            'warehouse' => $warehouse,
        ]);
    }
}
