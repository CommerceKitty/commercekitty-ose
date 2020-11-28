<?php

namespace CommerceKitty\Bundle\ExampleBundle;

use CommerceKitty\Bundle\ExampleBundle\DependencyInjection\CommerceKittyExampleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CommerceKittyExampleBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new CommerceKittyExampleExtension();
    }
}
