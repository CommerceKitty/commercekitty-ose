<?php

namespace CommerceKitty\Bundle\ExampleBundle;

//use CommerceKitty\Bundle\ExampleBundle\DependencyInjection\Compiler\CustomPass;
use CommerceKitty\Bundle\ExampleBundle\DependencyInjection\CommerceKittyExampleExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CommerceKittyExampleBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        //$container->addCompilerPass(new CustomPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new CommerceKittyExampleExtension();
    }
}
