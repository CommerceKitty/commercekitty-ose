<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductType;
use App\Form\Type\ProductTypeChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductController extends AbstractController
{
    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     *
     * @return Response
     */
    public function selectType(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $controllerEvent = $dispatcher->dispatch(new GenericEvent(null, ['request' => $request]), 'controller.product.select_type.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $form = $this->createForm(ProductTypeChoiceType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $request->getSession()->set('product_type', $form->getData());

            return $this->redirectToRoute('product_new');
        }

        return $this->render('product/select_type.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     *
     * @return Response
     */
    public function new(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        if (!$request->getSession()->has('product_type')) {
            $this->addFlash('warning', 'Invalid Request');

            return $this->redirectToRoute('product_select_type');
        }

        $entity = (new Product())
            ->setType($request->getSession()->get('product_type'))
        ;

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.product.new.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $form = $this->createForm(ProductType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entity);
            $manager->flush();

            $this->addFlash('success', $translator->trans('flashes.product.created.success', [
                '%entity_class_name%'      => 'Product',
                '%entity_full_class_name%' => Product::class,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            return $this->redirectToRoute('product_show', ['id' => $entity->getId()]);
        }

        return $this->render('product/new.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }
}
