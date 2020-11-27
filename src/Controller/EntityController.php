<?php

namespace App\Controller;

use App\Entity\AuditLog;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Symfony\Component\String\u;

/**
 * Main Controller used for basic entity actions such as CRUD. Use the
 * routes.yaml file to use this Controller.
 */
class EntityController extends AbstractController
{
    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     * @param PaginatorInterface       $paginator
     *
     * @return Response
     */
    public function index(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product

        $controllerEvent = $dispatcher->dispatch(new GenericEvent(null, ['request' => $request]), 'controller.'.$entitySnakeName.'.index.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $builder = $this->getDoctrine()->getRepository($entityFullClassName)
            ->createQueryBuilder('e')
        ;

        $pager = $paginator->paginate($builder, $request->query->getInt('page', 1), $request->query->getInt('limit', 10));

        return $this->render($templatePathPrefix.$entitySnakeName.'/index.'.$request->getRequestFormat().'.twig', [
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
    public function new(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product
        $formClassName       = $request->attributes->get('_form_class_name', $entityClassName.'Type'); // ie ProductType
        $formFullClassName   = $request->attributes->get('_form_class', 'App\\Form\\Type\\'.$formClassName); // ie App\Form\Type\ProductType
        $transId             = $request->attributes->get('_trans_id', $entitySnakeName);

        $entity = new $entityFullClassName();

        if (!$this->isGranted('create', $entity)) {
            throw $this->createNotFoundException($translator->trans('exceptions.'.$transId.'.create.403', [
                '%class%' => $entityClass,
            ]));
        }

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.'.$entitySnakeName.'.new.initialize');
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
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entity);
            $manager->flush();

            $event = new GenericEvent($entity, [
                'request' => $request,
            ]);

            $this->addFlash('success', $translator->trans('flashes.'.$transId.'.created.success', [
                '%entity_class_name%'      => $entityClassName,
                '%entity_full_class_name%' => $entityFullClassName,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            $responseEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'response.'.$entitySnakeName.'.created');
            if ($responseEvent->hasArgument('response')) {
                return $responseEvent->getArgument('response');
            }

            return $this->redirectToRoute($entitySnakeName.'_show', ['id' => $entity->getId()]);
        }

        return $this->render($templatePathPrefix.$entitySnakeName.'/new.'.$request->getRequestFormat().'.twig', [
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
    public function show(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product
        $transId             = $request->attributes->get('_trans_id', $entitySnakeName);

        $entity = $this->getDoctrine()->getRepository($entityFullClassName)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.'.$entitySnakeName.'.404', [
                '%entity_class_name%'      => $entityClassName,
                '%entity_full_class_name%' => $entityFullClassName,
                '%id%'                     => $id,
            ]));
        }

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.'.$entitySnakeName.'.show.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        return $this->render($templatePathPrefix.$entitySnakeName.'/show.'.$request->getRequestFormat().'.twig', [
            'entity' => $entity,
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
    public function edit(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product
        $formClassName       = $request->attributes->get('_form_class_name', $entityClassName.'Type'); // ie ProductType
        $formFullClassName   = $request->attributes->get('_form_class', 'App\\Form\\Type\\'.$formClassName); // ie App\Form\Type\ProductType
        $transId             = $request->attributes->get('_trans_id', $entitySnakeName);

        $entity = $this->getDoctrine()->getRepository($entityFullClassName)->find($id);
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
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entity);
            $manager->flush();

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

        return $this->render($templatePathPrefix.$entitySnakeName.'/edit.'.$request->getRequestFormat().'.twig', [
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
    public function delete(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product
        $transId             = $request->attributes->get('_trans_id', $entitySnakeName);

        $entity = $this->getDoctrine()->getRepository($entityFullClassName)->find($id);
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
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($entity);
            $manager->flush();

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

        return $this->render($templatePathPrefix.$entitySnakeName.'/delete.'.$request->getRequestFormat().'.twig', [
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
    public function clone(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product
        $transId             = $request->attributes->get('_trans_id', $entitySnakeName);

        $entity = $this->getDoctrine()->getRepository($entityFullClassName)->find($id);
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
            $cloneEntity = clone $entity;
            $manager      = $this->getDoctrine()->getManager();
            $manager->persist($cloneEntity);
            $manager->flush();

            $this->addFlash('success', $translator->trans('flashes.'.$transId.'.cloned.success', [
                '%entity_class_name%'      => $entityClassName,
                '%entity_full_class_name%' => $entityFullClassName,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            $responseEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request, 'clone' => $cloneEntity]), 'response.'.$entitySnakeName.'.cloned');
            if ($responseEvent->hasArgument('response')) {
                return $responseEvent->getArgument('response');
            }

            return $this->redirectToRoute($entitySnakeName.'_show', ['id' => $cloneEntity->getId()]);
        }

        return $this->render($templatePathPrefix.$entitySnakeName.'/clone.'.$request->getRequestFormat().'.twig', [
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
    public function purge(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
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
            $manager    = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository($entityFullClassName);
            $collection = $repository->findAll();
            foreach ($collection as $entity) {
                $manager->remove($entity);
            }
            $manager->flush();

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

        return $this->render($templatePathPrefix.$entitySnakeName.'/purge.'.$request->getRequestFormat().'.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     * @param PaginatorInterface       $paginator
     * @param int                      $id
     *
     * @return Response
     */
    public function log(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, PaginatorInterface $paginator, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product

        $entity = $this->getDoctrine()->getRepository($entityFullClassName)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('exceptions.'.$entitySnakeName.'.404', [
                '%entity_class_name%'      => $entityClassName,
                '%entity_full_class_name%' => $entityFullClassName,
                '%id%'                     => $id,
            ]));
        }

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.'.$entitySnakeName.'.log.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $builder = $this->getDoctrine()->getRepository(AuditLog::class)
            ->createQueryBuilder('l')
            ->where('l.entity = :entity AND l.entityId = :id')
            ->setParameters([
                'entity' => $entitySnakeName,
                'id'     => $entity->getId(),
            ])
            ->orderBy('l.createdAt', 'DESC')
        ;

        //if ($request->query->has('event')) {
        //    $builder->andWhere('l.event = :event')->setParameter('event', $request->query->get('event'));
        //}

        $pager = $paginator->paginate($builder, $request->query->getInt('page', 1), $request->query->getInt('limit', 10));

        return $this->render($templatePathPrefix.$entitySnakeName.'/log.'.$request->getRequestFormat().'.twig', [
            'entity' => $entity,
            'pager'  => $pager,
        ]);
    }
}
