<?php

namespace CommerceKitty\Controller;

use CommerceKitty\Entity;
use CommerceKitty\Event\ControllerEvent;
use CommerceKitty\Event\FormEvent;
use CommerceKitty\Event\TestConnectionEvent;
use CommerceKitty\Factory\ChannelEntityFactory;
use CommerceKitty\Factory\ChannelFormFactory;
use CommerceKitty\Factory\ChannelMessageFactory;
use CommerceKitty\Form\Type\ChannelTypeChoiceType;
use CommerceKitty\Message\Command\Channel\UpdateChannelCommand;
use CommerceKitty\Message\Query\Channel\FindChannelQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
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

        $controllerEvent = $dispatcher->dispatch(new ControllerEvent(null, $request), 'controller.channel.select_type.initialize');
        if ($controllerEvent->hasResponse()) {
            return $controllerEvent->getResponse();
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
    public function new(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, ChannelEntityFactory $entityFactory, ChannelFormFactory $formFactory, MessageBusInterface $eventBus): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        if (!$request->getSession()->has('channel_type')) {
            $this->addFlash('warning', 'Invalid Request');

            return $this->redirectToRoute('channel_select_type');
        }

        $controllerEvent = $dispatcher->dispatch(new ControllerEvent(null, $request), 'controller.channel.new.initialize');
        if ($controllerEvent->hasResponse()) {
            return $controllerEvent->getResponse();
        }

        $entityFullClassName = $entityFactory->getFullClassName($request->getSession()->get('channel_type'));
        $entity              = new $entityFullClassName();
        $formType            = $formFactory->getFullClassName($request->getSession()->get('channel_type'));
        $form                = $this->createForm($formType, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // @todo CommandBus
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entity);
            $manager->flush();

            //> Event Bus
            // @todo Move to Command Handler
            $eventNamespace     = 'App\\Message\\Event';
            $eventClassName     = 'CreatedChannelEvent';
            $eventFullClassName = $eventNamespace.'\\'.$eventClassName;
            if (class_exists($eventFullClassName)) {
                $eventBus->dispatch(new $eventFullClassName($entity));
            }
            //< Event Bus

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
    public function edit(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, ChannelFormFactory $formFactory, MessageBusInterface $commandBus, MessageBusInterface $eventBus, MessageBusInterface $queryBus, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entity = $queryBus->dispatch(new FindChannelQuery($id))->last(HandledStamp::class)->getResult();
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.channel.404', [
                '%entity_class_name%'      => 'Channel',
                '%entity_full_class_name%' => Entity\Channel::class,
                '%id%'                     => $id,
            ]));
        }

        $controllerEvent = $dispatcher->dispatch(new ControllerEvent($entity, $request), 'controller.channel.edit.initialize');
        if ($controllerEvent->hasResponse()) {
            return $controllerEvent->getResponse();
        }

        $formType = $formFactory->getFullClassName($entity->getType());
        $form     = $this->createForm($formType, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch(new UpdateChannelCommand($entity->getPayload()));

            //> Event Bus
            // @todo Move to Command Handler
            $eventFullClassName = 'App\\Message\\Event\\UpdatedChannelEvent';
            if (class_exists($eventFullClassName)) {
                $eventBus->dispatch(new $eventFullClassName($entity));
            }
            //< Event Bus

            $this->addFlash('success', $translator->trans('flashes.channel.updated.success', [
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

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     * @param string                   $id
     *
     * @return Response
     */
    public function testConnection(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, MessageBusInterface $queryBus, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entity = $queryBus->dispatch(new FindChannelQuery($id))->last(HandledStamp::class)->getResult();
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.channel.404', [
                '%entity_class_name%'      => 'Channel',
                '%entity_full_class_name%' => Entity\Channel::class,
                '%id%'                     => $id,
            ]));
        }

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('channel_test_connection', ['id' => $entity->getId()]))
            ->setMethod('POST')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // @todo Find a better way to do this
            $event = $dispatcher->dispatch(new TestConnectionEvent($entity), 'channel.'.$entity->getType().'.test_connection');
            if ($event->wasSuccessful()) {
                $this->addFlash('success', 'Connection Successful');
            } elseif (!$event->wasSuccessful() && $event->getMessage()) {
                $this->addFlash('danger', $event->getMessage());
            } else {
                $this->addFlash('danger', 'Connection Test Failed');
            }

            return $this->redirectToRoute('channel_show', ['id' => $entity->getId()]);
        }

        return $this->render('channel/test_connection.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     * @param MessageBusInterface      $commandBus
     * @param string                   $id
     *
     * @return Response
     */
    public function importProducts(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, MessageBusInterface $commandBus, ChannelMessageFactory $messageFactory, MessageBusInterface $queryBus, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entity = $queryBus->dispatch(new FindChannelQuery($id))->last(HandledStamp::class)->getResult();
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.channel.404', [
                '%entity_class_name%'      => 'Channel',
                '%entity_full_class_name%' => Entity\Channel::class,
                '%id%'                     => $id,
            ]));
        }

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('channel_import_products', ['id' => $entity->getId()]))
            ->setMethod('POST')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $messageFactory->getImportProductsMessage($entity);
            $message->setChannelId($entity->getId());
            $commandBus->dispatch($message);

            $this->addFlash('success', $translator->trans('flashes.channel.import_products.success', [
                '%entity_class_name%'      => 'Channel',
                '%entity_full_class_name%' => Entity\Channel::class,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            return $this->redirectToRoute('channel_show', ['id' => $entity->getId()]);
        }

        return $this->render('channel/import_products.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     * @param MessageBusInterface      $commandBus
     * @param string                   $id
     *
     * @return Response
     */
    public function importOrders(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, MessageBusInterface $commandBus, ChannelMessageFactory $messageFactory, MessageBusInterface $queryBus, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entity = $queryBus->dispatch(new FindChannelQuery($id))->last(HandledStamp::class)->getResult();
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.channel.404', [
                '%entity_class_name%'      => 'Channel',
                '%entity_full_class_name%' => Entity\Channel::class,
                '%id%'                     => $id,
            ]));
        }

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('channel_import_orders', ['id' => $entity->getId()]))
            ->setMethod('POST')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $messageFactory->getImportOrdersMessage($entity);
            $message->setChannelId($entity->getId());
            $commandBus->dispatch($message);

            $this->addFlash('success', $translator->trans('flashes.channel.import_orders.success', [
                '%entity_class_name%'      => 'Channel',
                '%entity_full_class_name%' => Entity\Channel::class,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            return $this->redirectToRoute('channel_show', ['id' => $entity->getId()]);
        }

        return $this->render('channel/import_orders.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     * @param MessageBusInterface      $commandBus
     * @param string                   $id
     *
     * @return Response
     */
    public function exportInventory(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, MessageBusInterface $commandBus, ChannelMessageFactory $messageFactory, MessageBusInterface $queryBus, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entity = $queryBus->dispatch(new FindChannelQuery($id))->last(HandledStamp::class)->getResult();
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.channel.404', [
                '%entity_class_name%'      => 'Channel',
                '%entity_full_class_name%' => Entity\Channel::class,
                '%id%'                     => $id,
            ]));
        }

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('channel_export_inventory', ['id' => $entity->getId()]))
            ->setMethod('POST')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $messageFactory->getExportInventoryMessage($entity);
            $message->setChannelId($entity->getId());
            $commandBus->dispatch($message);

            $this->addFlash('success', $translator->trans('flashes.channel.export_inventory.success', [
                '%entity_class_name%'      => 'Channel',
                '%entity_full_class_name%' => Entity\Channel::class,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            return $this->redirectToRoute('channel_show', ['id' => $entity->getId()]);
        }

        return $this->render('channel/export_inventory.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     * @param MessageBusInterface      $commandBus
     * @param string                   $id
     *
     * @return Response
     */
    public function exportListings(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, MessageBusInterface $commandBus, ChannelMessageFactory $messageFactory, MessageBusInterface $queryBus, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entity = $queryBus->dispatch(new FindChannelQuery($id))->last(HandledStamp::class)->getResult();
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.channel.404', [
                '%entity_class_name%'      => 'Channel',
                '%entity_full_class_name%' => Entity\Channel::class,
                '%id%'                     => $id,
            ]));
        }

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('channel_export_listings', ['id' => $entity->getId()]))
            ->setMethod('POST')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $messageFactory->getExportListingsMessage($entity);
            $message->setChannelId($entity->getId());
            $commandBus->dispatch($message);

            $this->addFlash('success', $translator->trans('flashes.channel.export_listings.success', [
                '%entity_class_name%'      => 'Channel',
                '%entity_full_class_name%' => Entity\Channel::class,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            return $this->redirectToRoute('channel_show', ['id' => $entity->getId()]);
        }

        return $this->render('channel/export_listings.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }
}
