<?php

namespace App\Controller;

use App\Entity;
use App\Form\Type\ChannelTypeChoiceType;
use App\Form\Type\WoocommerceChannelType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChannelController extends AbstractController
{
    /**
     * Allow the user to select the type of channel (eBay, Amazon, FTP, etc.)
     *
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     *
     * @return Response
     */
    public function selectType(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $controllerEvent = $dispatcher->dispatch(new GenericEvent(null, ['request' => $request]), 'controller.channel.select_type.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $form = $this->createForm(ChannelTypeChoiceType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $request->getSession()->set('channel_type', $form->getData());

            return $this->redirectToRoute('channel_new');
        }

        return $this->render('channel/select_type.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Create the channel based on the type the user selected
     *
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     *
     * @return Response
     */
    public function new(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        if (!$request->getSession()->has('channel_type')) {
            $this->addFlash('warning', 'Invalid Request');

            return $this->redirectToRoute('channel_select_type');
        }

        $controllerEvent = $dispatcher->dispatch(new GenericEvent(null, ['request' => $request]), 'controller.channel.new.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        // @TODO use Factory
        switch ($request->getSession()->get('channel_type')) {
            case('woocommerce'):
                $entity   = new Entity\WoocommerceChannel();
                $formType = WoocommerceChannelType::class;
                break;
            default:
                throw new \Exception('Channel Type is unknown');
        }

        // @todo Use form event
        $form = $this->createForm($formType, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entity);
            $manager->flush();

            $request->getSession()->remove('channel_type');

            $this->addFlash('success', $translator->trans('flashes.channel.created.success', [
                '%entity_class_name%'      => 'Channel',
                '%entity_full_class_name%' => Entity\Channel::class,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            return $this->redirectToRoute('channel_show', ['id' => $entity->getId()]);
        }

        return $this->render('channel/new.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     *
     * @return Response
     */
    public function edit(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entity = $this->getDoctrine()->getRepository(Entity\Channel::class)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.channel.404', [
                '%entity_class_name%'      => 'Channel',
                '%entity_full_class_name%' => Entity\Channel::class,
                '%id%'                     => $id,
            ]));
        }

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.channel.edit.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        // @TODO Use Factory
        switch ($entity->getType()) {
            case('woocommerce'):
                $formType = WoocommerceChannelType::class;
                break;
            default:
                throw new \Exception('Channel Type is unknown');
        }

        // @todo Use form event
        $form = $this->createForm($formType, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entity);
            $manager->flush();

            $request->getSession()->remove('channel_type');

            $this->addFlash('success', $translator->trans('flashes.channel.created.success', [
                '%entity_class_name%'      => 'Channel',
                '%entity_full_class_name%' => Entity\Channel::class,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            return $this->redirectToRoute('channel_show', ['id' => $entity->getId()]);
        }

        return $this->render('channel/edit.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }
}
