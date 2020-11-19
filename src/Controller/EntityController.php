<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
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
     *
     * @return Response
     */
    public function index(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product

        // dispatch -> controller.{entity_snake_case}.{action}.initialize

        return $this->render($templatePathPrefix.$entitySnakeName.'/index.'.$request->getRequestFormat().'.twig', [
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     *
     * @return Response
     */
    public function new(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product
        $formClassName       = $request->attributes->get('_form_class_name'); // ie ProductType
        $formFullClassName   = $request->attributes->get('_form_class'); // ie App\Form\Type\ProductType

        // dispatch -> controller.{entity_snake_case}.{action}.initialize

        return $this->render($templatePathPrefix.$entitySnakeName.'/new.'.$request->getRequestFormat().'.twig', [
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     *
     * @return Response
     */
    public function show(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product

        // dispatch -> controller.{entity_snake_case}.{action}.initialize

        return $this->render($templatePathPrefix.$entitySnakeName.'/new.'.$request->getRequestFormat().'.twig', [
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     *
     * @return Response
     */
    public function edit(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product
        $formClassName       = $request->attributes->get('_form_class_name'); // ie ProductType
        $formFullClassName   = $request->attributes->get('_form_class'); // ie App\Form\Type\ProductType

        // dispatch -> controller.{entity_snake_case}.{action}.initialize

        return $this->render($templatePathPrefix.$entitySnakeName.'/edit.'.$request->getRequestFormat().'.twig', [
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     *
     * @return Response
     */
    public function delete(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product

        // dispatch -> controller.{entity_snake_case}.{action}.initialize

        return $this->render($templatePathPrefix.$entitySnakeName.'/delete.'.$request->getRequestFormat().'.twig', [
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     *
     * @return Response
     */
    public function clone(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product

        // dispatch -> controller.{entity_snake_case}.{action}.initialize

        return $this->render($templatePathPrefix.$entitySnakeName.'/clone.'.$request->getRequestFormat().'.twig', [
        ]);
    }

    /**
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     *
     * @return Response
     */
    public function purge(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $templatePathPrefix  = $request->attributes->get('_template_path_prefix');
        $entityClassName     = $request->attributes->get('_entity_class_name'); // ie Product
        $entityFullClassName = $request->attributes->get('_entity_class', 'App\\Entity\\'.$entityClassName); // ie App\Entity\Product
        $entitySnakeName     = u($entityClassName)->snake(); // ie product

        // dispatch -> controller.{entity_snake_case}.{action}.initialize

        return $this->render($templatePathPrefix.$entitySnakeName.'/purge.'.$request->getRequestFormat().'.twig', [
        ]);
    }
}
