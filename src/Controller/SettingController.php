<?php declare(strict_types=1);

namespace CommerceKitty\Controller;

use CommerceKitty\Event\ControllerEvent;
use CommerceKitty\Event\FormEvent;
use CommerceKitty\Form\Type\AppSettingsType;
use CommerceKitty\Message\Command\Setting\UpdateSettingsCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SettingController extends AbstractController
{
    private $commandBus;
    private $dispatcher;

    public function __construct(MessageBusInterface $commandBus, EventDispatcherInterface $dispatcher)
    {
        $this->commandBus = $commandBus;
        $this->dispatcher = $dispatcher;
    }

    /**
     */
    public function edit(Request $request): Response
    {
        $form = $this->createForm(AppSettingsType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->dispatch(new UpdateSettingsCommand($form->getData(), ['user_id' => $this->getUser()->getId()]));

            $this->addFlash('success', 'Settings Updated');

            return $this->redirectToRoute('settings_edit');
        }

        return $this->render('setting/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
