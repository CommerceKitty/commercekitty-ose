<?php
/**
 * @see https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html
 */

namespace CommerceKitty\DataFixtures;

use CommerceKitty\Entity;
use CommerceKitty\Model\ProductInterface;
use Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }

    /**
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getTemplates() as $tmpl) {
            $entity = (new Entity\Product())
                ->setId((string) Uuid::v6())
                ->setName($tmpl['name'])
                ->setSku($tmpl['sku'])
                ->setType($tmpl['type'])
            ;
            $manager->persist($entity);
            $this->addReference('product-'.$tmpl['sku'], $entity);
        }

        $manager->flush();
    }

    /**
     */
    private function getTemplates(): Generator
    {
        yield [
            'name' => 'Widget 001',
            'sku'  => 'WIDGET001',
            'type' => ProductInterface::TYPE_SIMPLE,
        ];
    }
}
