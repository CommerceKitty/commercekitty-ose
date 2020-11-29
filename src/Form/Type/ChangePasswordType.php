<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('current_password', PasswordType::class)
            ->add('plain_password', RepeatedType::class, [
                'type'           => PasswordType::class,
                'first_options'  => ['label' => 'password'],
                'second_options' => ['label' => 'repeat password'],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => User::class,
            'translation_domain' => 'forms',
            'validation_groups'  => ['change_password'],
        ]);
    }
}
