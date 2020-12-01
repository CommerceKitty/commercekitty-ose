<?php
/**
 * @see https://symfony.com/doc/current/form/inherit_data_option.html
 */

namespace App\Form\Type;

use App\Entity\ShopifyChannel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopifyChannelType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('channel', ChannelType::class, [
                'data_class' => ShopifyChannel::class,
            ])
            ->add('host', UrlType::class, [
                'required' => true,
            ])
            ->add('api_key', TextType::class, [
                'required' => true,
            ])
            ->add('password', TextType::class, [
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
            'data_class'         => ShopifyChannel::class,
            'translation_domain' => 'forms'
        ]);
    }
}
