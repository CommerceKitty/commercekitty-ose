<?php declare(strict_types=1);

namespace CommerceKitty\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppSettingsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currency', ChoiceType::class, [
                'required'    => false,
                'empty_data'  => 'USD',
                'placeholder' => 'Select Currency',
                'choices'     => [
                    'US Dollar' => 'USD',
                ],
            ])
            ->add('language', ChoiceType::class, [
                'required'    => false,
                'empty_data'  => 'en',
                'placeholder' => 'Select Language',
                'choices'     => [
                    'English' => 'en',
                ],
            ])
            ->add('timezone', TimezoneType::class, [
                'required'          => false,
                'empty_data'        => 'America/New_York',
                'placeholder'       => 'Select Timezone',
                'preferred_choices' => [
                    'America/New_York',
                ]
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => null,
            'translation_domain' => 'forms'
        ]);
    }
}
