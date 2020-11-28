<?php
/**
 * @see https://symfony.com/doc/current/form/inherit_data_option.html
 */

namespace App\Form\Type;

use App\Entity\WoocommerceChannel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WoocommerceChannelType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('channel', ChannelType::class, [
                'data_class' => WoocommerceChannel::class,
            ])
            ->add('host', UrlType::class, [
                'required' => true,
            ])
            ->add('consumer_key', TextType::class, [
                'required' => true,
            ])
            ->add('consumer_secret', TextType::class, [
                'required' => true,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => WoocommerceChannel::class,
            'translation_domain' => 'forms'
        ]);
    }
}
