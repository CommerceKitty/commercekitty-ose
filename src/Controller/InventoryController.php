<?php

namespace App\Controller;

use App\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class InventoryController extends AbstractController
{
    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     * @param string                   $id
     *
     * @return Response
     */
    public function set(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entity = $this->getDoctrine()->getRepository(Entity\Inventory::class)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.inventory.404', [
                '%entity_class_name%'      => 'Inventory',
                '%entity_full_class_name%' => Entity\Inventory::class,
                '%id%'                     => $id,
            ]));
        }

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.inventory.set.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $form = $this->createForm(IntegerType::class, null, [
            'label'              => 'set quantity to',
            'translation_domain' => 'forms',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity->setQuantity($form->getData());

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entity);
            $manager->flush();

            $this->addFlash('success', $translator->trans('flashes.inventory.set.success', [
                '%entity_class_name%'      => 'Inventory',
                '%entity_full_class_name%' => Entity\Inventory::class,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            return $this->redirectToRoute('product_inventory', ['id' => $entity->getProduct()->getId()]);
        }


        return $this->render('inventory/set.html.twig', [
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
    public function increment(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entity = $this->getDoctrine()->getRepository(Entity\Inventory::class)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.inventory.404', [
                '%entity_class_name%'      => 'Inventory',
                '%entity_full_class_name%' => Entity\Inventory::class,
                '%id%'                     => $id,
            ]));
        }

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.inventory.increment.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $form = $this->createForm(IntegerType::class, null, [
            'label'              => 'increment quantity by',
            'translation_domain' => 'forms'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $quantity = ($entity->getQuantity() + $form->getData());
            $entity->setQuantity($quantity);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entity);
            $manager->flush();

            $this->addFlash('success', $translator->trans('flashes.inventory.increment.success', [
                '%entity_class_name%'      => 'Inventory',
                '%entity_full_class_name%' => Entity\Inventory::class,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            return $this->redirectToRoute('product_inventory', ['id' => $entity->getProduct()->getId()]);
        }


        return $this->render('inventory/increment.html.twig', [
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
    public function decrement(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entity = $this->getDoctrine()->getRepository(Entity\Inventory::class)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.inventory.404', [
                '%entity_class_name%'      => 'Inventory',
                '%entity_full_class_name%' => Entity\Inventory::class,
                '%id%'                     => $id,
            ]));
        }

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.inventory.decrement.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $form = $this->createForm(IntegerType::class, null, [
            'label'              => 'decrement quantity by',
            'translation_domain' => 'forms'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $quantity = ($entity->getQuantity() - $form->getData());
            $entity->setQuantity($quantity);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entity);
            $manager->flush();

            $this->addFlash('success', $translator->trans('flashes.inventory.decrement.success', [
                '%entity_class_name%'      => 'Inventory',
                '%entity_full_class_name%' => Entity\Inventory::class,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            return $this->redirectToRoute('product_inventory', ['id' => $entity->getProduct()->getId()]);
        }


        return $this->render('inventory/decrement.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }
}
