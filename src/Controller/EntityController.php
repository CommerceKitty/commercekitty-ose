<?php declare(strict_types=1);

namespace CommerceKitty\Controller;

use CommerceKitty\Event\ControllerEvent;
use CommerceKitty\Event\QueryBuilderEvent;
use CommerceKitty\HandleTrait;
use CommerceKitty\Model\PayloadableInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Symfony\Component\String\u;

/**
 * Main Controller used for basic entity actions such as CRUD. Use the
 * routes.yaml file to use this Controller.
 */
class EntityController extends AbstractController
{
    use HandleTrait;

    private $commandBus;
    private $dispatcher;
    private $translator;

    public function __construct(TranslatorInterface $translator, EventDispatcherInterface $dispatcher, MessageBusInterface $commandBus, MessageBusInterface $queryBus)
    {
        $this->translator = $translator;
        $this->commandBus = $commandBus;
        $this->dispatcher = $dispatcher;
        $this->queryBus   = $queryBus;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $this->translator->trans('exceptions.403'));

        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'CommerceKitty\\Entity\\'.$entityClassName); // ie CommerceKitty\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product

        //> Event Dispatcher
        $controllerEvent = $this->dispatcher->dispatch(new ControllerEvent(null, $request), 'controller.'.$entitySnakeName.'.index.initialize');
        if ($controllerEvent->hasResponse()) {
            return $controllerEvent->getResponse();
        }
        //< Event Dispatcher

        //> Event Dispatcher
        // @todo Use the Entity Repository method "getQueryBuilderWithRequest"; check if method exists
        $builder = $this->getDoctrine()->getRepository($entityFullClassName)
            ->createQueryBuilder('e')
        ;
        $qBuilder = $this->dispatcher
            ->dispatch(new QueryBuilderEvent($request, $builder), 'query_builder.'.$entitySnakeName.'.build')
            ->getQueryBuilder()
        ;
        //< Event Dispatcher

        $pager = $paginator->paginate(
            $qBuilder,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 100),
        );

        return $this->render($entitySnakeName.'/index.'.$request->getRequestFormat().'.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     *
     * @return Response
     */
    public function new(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, MessageBusInterface $commandBus, MessageBusInterface $eventBus): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'CommerceKitty\\Entity\\'.$entityClassName); // ie CommerceKitty\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product
        $formClassName       = $request->attributes->get('_form_class_name', $entityClassName.'Type'); // ie ProductType
        $formFullClassName   = $request->attributes->get('_form_class', 'CommerceKitty\\Form\\Type\\'.$formClassName); // ie CommerceKitty\Form\Type\ProductType
        $transId             = $request->attributes->get('_trans_id', $entitySnakeName);

        // @todo Factory to create entity
        $entity = new $entityFullClassName();
        if (!$entity instanceof PayloadableInterface) {
            throw new \Exception(sprintf('Class "%s" must implement "%s"', $entityFullClassName, PayloadableInterface::class));
        }

        //> Event Dispatcher
        $controllerEvent = $dispatcher->dispatch(new ControllerEvent($entity, $request), 'controller.'.$entitySnakeName.'.new.initialize');
        if ($controllerEvent->hasResponse()) {
            return $controllerEvent->getResponse();
        }
        //< Event Dispatcher

        //> Event Dispatcher
        // @todo FormEvent
        $formEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.form.'.$entitySnakeName.'.initialize');
        if ($formEvent->hasArgument('form')) {
            $form = $formEvent->getArgument('form');
        } else {
            $form = $this->createForm($formFullClassName, $entity);
        }
        //< Event Dispatcher

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity->setId((string) Uuid::v6());

            $commandNamespace     = 'CommerceKitty\\Message\\Command\\'.$entityClassName;
            $commandClassName     = 'Create'.$entityClassName.'Command'; // ie: CreateProductCommand
            $commandFullClassName = $commandNamespace.'\\'.$commandClassName;
            $payload              = $entity->toPayload();

            if (!is_array($payload)) {
                throw new \Exception('sort your shit out');
            }

            $this->commandBus->dispatch(new $commandFullClassName($payload, ['user_id' => $this->getUser()->getId()]));

            $this->addFlash('success', $translator->trans('flashes.'.$transId.'.created.success', [
                '%entity_class_name%'      => $entityClassName,
                '%entity_full_class_name%' => $entityFullClassName,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            //> Event Dispatcher
            $responseEvent = $dispatcher->dispatch(new ControllerEvent($entity, $request), 'response.'.$entitySnakeName.'.created');
            if ($responseEvent->hasResponse()) {
                return $responseEvent->getResponse();
            }
            //< Event Dispatcher

            return $this->redirectToRoute($entitySnakeName.'_show', ['id' => $entity->getId()]);
        }

        return $this->render($entitySnakeName.'/new.'.$request->getRequestFormat().'.twig', [
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
    public function show(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, MessageBusInterface $queryBus, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'CommerceKitty\\Entity\\'.$entityClassName); // ie CommerceKitty\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product
        $transId             = $request->attributes->get('_trans_id', $entitySnakeName);

        //> Query Bus
        $queryFullClassName = 'CommerceKitty\\Message\\Query\\'.$entityClassName.'\\Find'.$entityClassName.'Query';
        $entity             = $queryBus->dispatch(new $queryFullClassName($id))->last(HandledStamp::class)->getResult();
        //< Query Bus

        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.'.$entitySnakeName.'.404', [
                '%entity_class_name%'      => $entityClassName,
                '%entity_full_class_name%' => $entityFullClassName,
                '%id%'                     => $id,
            ]));
        }

        //> Event Dispatcher
        $controllerEvent = $dispatcher->dispatch(new ControllerEvent($entity, $request), 'controller.'.$entitySnakeName.'.show.initialize');
        if ($controllerEvent->hasResponse()) {
            return $controllerEvent->getResponse();
        }
        //< Event Dispatcher

        return $this->render($entitySnakeName.'/show.'.$request->getRequestFormat().'.twig', [
            'entity' => $entity,
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
    public function edit(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, MessageBusInterface $queryBus, MessageBusInterface $eventBus, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'CommerceKitty\\Entity\\'.$entityClassName); // ie CommerceKitty\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product
        $formClassName       = $request->attributes->get('_form_class_name', $entityClassName.'Type'); // ie ProductType
        $formFullClassName   = $request->attributes->get('_form_class', 'CommerceKitty\\Form\\Type\\'.$formClassName); // ie CommerceKitty\Form\Type\ProductType
        $transId             = $request->attributes->get('_trans_id', $entitySnakeName);

        //> Query Bus
        $queryFullClassName = 'CommerceKitty\\Message\\Query\\'.$entityClassName.'\\Find'.$entityClassName.'Query';
        $entity             = $this->handle(new $queryFullClassName($id));
        //< Query Bus

        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.'.$entitySnakeName.'.404', [
                '%entity_class_name%'      => $entityClassName,
                '%entity_full_class_name%' => $entityFullClassName,
                '%id%'                     => $id,
            ]));
        }

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.'.$entitySnakeName.'.edit.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $formEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.form.'.$entitySnakeName.'.initialize');
        if ($formEvent->hasArgument('form')) {
            $form = $formEvent->getArgument('form');
        } else {
            $form = $this->createForm($formFullClassName, $entity);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commandNamespace     = 'CommerceKitty\\Message\\Command\\'.$entityClassName;
            $commandClassName     = 'Update'.$entityClassName.'Command'; // ie: UpdateProductCommand
            $commandFullClassName = $commandNamespace.'\\'.$commandClassName;
            $payload              = $entity->toPayload();

            if (!is_array($payload)) {
                throw new \Exception('sort your shit out');
            }

            $this->commandBus->dispatch(new $commandFullClassName($payload, ['user_id' => $this->getUser()->getId()]));

            $this->addFlash('success', $translator->trans('flashes.'.$transId.'.updated.success', [
                '%entity_class_name%'      => $entityClassName,
                '%entity_full_class_name%' => $entityFullClassName,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            $responseEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'response.'.$entitySnakeName.'.updated');
            if ($responseEvent->hasArgument('response')) {
                return $responseEvent->getArgument('response');
            }

            return $this->redirectToRoute($entitySnakeName.'_show', ['id' => $entity->getId()]);
        }

        return $this->render($entitySnakeName.'/edit.'.$request->getRequestFormat().'.twig', [
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
    public function delete(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, MessageBusInterface $queryBus, MessageBusInterface $eventBus, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'CommerceKitty\\Entity\\'.$entityClassName); // ie CommerceKitty\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product
        $transId             = $request->attributes->get('_trans_id', $entitySnakeName);

        //> Query Bus
        $queryFullClassName = 'CommerceKitty\\Message\\Query\\'.$entityClassName.'\\Find'.$entityClassName.'Query';
        $entity             = $queryBus->dispatch(new $queryFullClassName($id))->last(HandledStamp::class)->getResult();
        //< Query Bus

        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.'.$entitySnakeName.'.404', [
                '%entity_class_name%'      => $entityClassName,
                '%entity_full_class_name%' => $entityFullClassName,
                '%id%'                     => $id,
            ]));
        }

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.'.$entitySnakeName.'.delete.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl($entitySnakeName.'_delete', ['id' => $entity->getId()]))
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commandNamespace     = 'CommerceKitty\\Message\\Command\\'.$entityClassName;
            $commandClassName     = 'Delete'.$entityClassName.'Command'; // ie: DeleteProductCommand
            $commandFullClassName = $commandNamespace.'\\'.$commandClassName;

            $this->commandBus->dispatch(new $commandFullClassName(['id' => $entity->getId()], ['user_id' => $this->getUser()->getId()]));

            $this->addFlash('success', $translator->trans('flashes.'.$transId.'.deleted.success', [
                '%entity_class_name%'      => $entityClassName,
                '%entity_full_class_name%' => $entityFullClassName,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            $responseEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'response.'.$entitySnakeName.'.deleted');
            if ($responseEvent->hasArgument('response')) {
                return $responseEvent->getArgument('response');
            }

            return $this->redirectToRoute($entitySnakeName.'_index');
        }

        return $this->render($entitySnakeName.'/delete.'.$request->getRequestFormat().'.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     * @param int                      $id
     *
     * @return Response
     */
    public function clone(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, MessageBusInterface $queryBus, MessageBusInterface $eventBus, string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'CommerceKitty\\Entity\\'.$entityClassName); // ie CommerceKitty\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product
        $transId             = $request->attributes->get('_trans_id', $entitySnakeName);

        //> Query Bus
        $queryFullClassName = 'CommerceKitty\\Message\\Query\\'.$entityClassName.'\\Find'.$entityClassName.'Query';
        $entity             = $queryBus->dispatch(new $queryFullClassName($id))->last(HandledStamp::class)->getResult();
        //< Query Bus

        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.'.$entitySnakeName.'.404', [
                '%entity_class_name%'      => $entityClassName,
                '%entity_full_class_name%' => $entityFullClassName,
                '%id%'                     => $id,
            ]));
        }

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.'.$entitySnakeName.'.clone.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl($entitySnakeName.'_clone', ['id' => $entity->getId()]))
            ->setMethod('POST')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commandNamespace     = 'CommerceKitty\\Message\\Command\\'.$entityClassName;
            $commandClassName     = 'Clone'.$entityClassName.'Command'; // ie: CloneProductCommand
            $commandFullClassName = $commandNamespace.'\\'.$commandClassName;

            $payload = [
                'id'       => $entity->getId(),
                'clone_id' => (string) Uuid::v6(),
            ];

            $this->commandBus->dispatch(new $commandFullClassName($payload, ['user_id' => $this->getUser()->getId()]));

            $this->addFlash('success', $translator->trans('flashes.'.$transId.'.cloned.success', [
                '%entity_class_name%'      => $entityClassName,
                '%entity_full_class_name%' => $entityFullClassName,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            $responseEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'response.'.$entitySnakeName.'.cloned');
            if ($responseEvent->hasArgument('response')) {
                return $responseEvent->getArgument('response');
            }

            return $this->redirectToRoute($entitySnakeName.'_show', ['id' => $payload['clone_id']]);
        }

        return $this->render($entitySnakeName.'/clone.'.$request->getRequestFormat().'.twig', [
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
    public function purge(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, MessageBusInterface $eventBus): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'CommerceKitty\\Entity\\'.$entityClassName); // ie CommerceKitty\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product
        $transId             = $request->attributes->get('_trans_id', $entitySnakeName);

        $controllerEvent = $dispatcher->dispatch(new GenericEvent(null, ['request' => $request]), 'controller.'.$entitySnakeName.'.purge.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl($entitySnakeName.'_purge'))
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commandNamespace     = 'CommerceKitty\\Message\\Command\\'.$entityClassName;
            $commandClassName     = 'Purge'.$entityClassName.'Command'; // ie: PurgeProductCommand
            $commandFullClassName = $commandNamespace.'\\'.$commandClassName;

            $this->commandBus->dispatch(new $commandFullClassName([], ['user_id' => $this->getUser()->getId()]));

            $this->addFlash('success', $translator->trans('flashes.'.$transId.'.purged.success', [
                '%entity_class_name%'      => $entityClassName,
                '%entity_full_class_name%' => $entityFullClassName,
            ], 'flashes'));

            $responseEvent = $dispatcher->dispatch(new GenericEvent(null, ['request' => $request]), 'response.'.$entitySnakeName.'.purged');
            if ($responseEvent->hasArgument('response')) {
                return $responseEvent->getArgument('response');
            }

            return $this->redirectToRoute($entitySnakeName.'_index');
        }

        return $this->render($entitySnakeName.'/purge.'.$request->getRequestFormat().'.twig', [
            'form' => $form->createView(),
        ]);
    }
}
