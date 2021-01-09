<?php

namespace CommerceKitty\Form\Type;

use CommerceKitty\Model\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('phone', TextType::class, [
                'required' => false,
            ])
            ->add('first_name', TextType::class, [
                'required' => false,
            ])
            ->add('last_name', TextType::class, [
                'required' => false,
            ])
            ->add('company_name', TextType::class, [
                'required' => false,
            ])
            ->add('address_one', TextType::class, [
                'required' => false,
            ])
            ->add('address_two', TextType::class, [
                'required' => false,
            ])
            ->add('state', TextType::class, [
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'required' => false,
            ])
            ->add('county', TextType::class, [
                'required' => false,
            ])
            ->add('postal_code', TextType::class, [
                'required' => false,
            ])
            ->add('country', CountryType::class, [
                'required' => false,
            ])
            ->add('country_code', TextType::class, [
                'required' => false,
            ])
            ->add('latitude', NumberType::class, [
                'required' => false,
                'scale'    => 6,
            ])
            ->add('longitude', NumberType::class, [
                'required' => false,
                'scale'    => 6,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => Address::class,
            'translation_domain' => 'forms'
        ]);
    }
}
