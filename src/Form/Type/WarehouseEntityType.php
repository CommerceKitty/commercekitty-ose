<?php
/**
 * @see https://symfony.com/doc/current/reference/forms/types/entity.html
 * @see https://symfony.com/doc/current/form/create_custom_field_type.html
 */

namespace App\Form\Type;

use App\Entity;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WarehouseEntityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label'              => false,
            'placeholder'        => 'Please Select a Warehouse',
            'class'              => Entity\Warehouse::class,
            'translation_domain' => 'forms',
            'query_builder'      => function (EntityRepository $er) {
                return $er->createQueryBuilder('w')
                    ->orderBy('w.name', 'ASC');
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return EntityType::class;
    }
}
