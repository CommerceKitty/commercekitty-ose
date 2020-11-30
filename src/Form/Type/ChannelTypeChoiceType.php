<?php

namespace App\Form\Type;

use App\Model\ProductInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChannelTypeChoiceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label'       => false,
            'placeholder' => 'Please Select a Channel Type',
            'choices'     => [
                // Label => Value
                //'Amazon'      => 'amazon',
                //'eBay'        => 'ebay',
                'Woocommerce' => 'woocommerce',
            ],
            'translation_domain' => 'forms'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
