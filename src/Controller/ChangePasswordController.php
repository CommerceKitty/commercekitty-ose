<?php

namespace CommerceKitty\Controller;

use CommerceKitty\Entity;
use CommerceKitty\Event\ControllerEvent;
use CommerceKitty\Event\FormEvent;
use CommerceKitty\Form\Type\ChangePasswordType;
use CommerceKitty\Message\Command\User\ChangePasswordCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordController extends AbstractController
{
    /**
     * @param Request                      $request
     * @param EventDispatcherInterface     $dispatcher
     * @param TranslatorInterface          $translator
     *
     * @return Response
     */
    public function changePassword(Request $request, EventDispatcherInterface $dispatcher, TranslatorInterface $translator, MessageBusInterface $commandBus): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, $translator->trans('exceptions.403'));

        $entity = $this->getUser();

        $controllerEvent = $dispatcher->dispatch(new ControllerEvent($entity, $request), 'controller.change_password.change_password.initialize');
        if ($controllerEvent->hasResponse()) {
            return $controllerEvent->getResponse();
        }

        $formEvent = $dispatcher->dispatch(new FormEvent($entity, $request), 'controller.form.change_password.initialize');
        if ($formEvent->hasForm()) {
            $form = $formEvent->getForm();
        } else {
            $form = $this->createForm(ChangePasswordType::class, $entity);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // @todo encrypt/decrypt pw or send pw hash
            $commandPayload = [
                'user_id'        => $entity->getId(),
                'plain_password' => $entity->getPlainPassword(),
            ];
            $commandBus->dispatch(new ChangePasswordCommand($commandPayload));

            //$passwordHash = $passwordEncoder->encodePassword($entity, $entity->getPlainPassword());
            //$entity->setPassword($passwordHash);
            //$manager = $this->getDoctrine()->getManager();
            //$manager->persist($entity);
            //$manager->flush();

            $this->addFlash('success', $translator->trans('flashes.change_password.updated.success', [
                '%entity_class_name%'      => 'User',
                '%entity_full_class_name%' => Entity\User::class,
                '%string%'                 => method_exists($entity, '__toString') ? $entity->__toString() : '',
            ], 'flashes'));

            $controllerEvent = $dispatcher->dispatch(new ControllerEvent($entity, $request), 'response.change_password.updated');
            if ($controllerEvent->hasResponse()) {
                return $controllerEvent->getResponse();
            }

            return $this->redirectToRoute('homepage');
        }

        return $this->render('change_password/change_password.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }
}
