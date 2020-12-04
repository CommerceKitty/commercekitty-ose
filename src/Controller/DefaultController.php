<?php

namespace CommerceKitty\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     */
    public function index()
    {
        return $this->render('default/index.html.twig', []);
    }
}
