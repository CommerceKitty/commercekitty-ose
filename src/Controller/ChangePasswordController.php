<?php

namespace CommerceKitty\Controller;

use CommerceKitty\Entity;
use CommerceKitty\Form\Type\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordController extends AbstractController
{
    /**
     * @param Request                      $request
     * @param EventDispatcherInterface     $dispatcher
     * @param TranslatorInterface          $translator
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     */
    public function changePassword(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entity = $this->getUser();

        $controllerEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.change_password.change_password.initialize');
        if ($controllerEvent->hasArgument('response')) {
            return $controllerEvent->getArgument('response');
        }

        $formEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'controller.form.change_password.initialize');
        if ($formEvent->hasArgument('form')) {
            $form = $formEvent->getArgument('form');
        } else {
            $form = $this->createForm(ChangePasswordType::class, $entity);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $passwordHash = $passwordEncoder->encodePassword($entity, $entity->getPlainPassword());
            $entity->setPassword($passwordHash);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entity);
            $manager->flush();

            $this->addFlash('success', $translator->trans('flashes.change_password.updated.success', [
                '%entity_class_name%'      => 'User',
                '%entity_full_class_name%' => Entity\User::class,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            $responseEvent = $dispatcher->dispatch(new GenericEvent($entity, ['request' => $request]), 'response.change_password.updated');
            if ($responseEvent->hasArgument('response')) {
                return $responseEvent->getArgument('response');
            }

            return $this->redirectToRoute('homepage');
        }

        return $this->render('change_password/change_password.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }
}
