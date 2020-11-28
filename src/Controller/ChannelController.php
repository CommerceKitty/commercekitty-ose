<?php

namespace App\Controller;

use App\ChannelRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        return $this->render('channel/select_type.html.twig', [
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
        return $this->render('channel/new.html.twig', [
        ]);
    }
}
