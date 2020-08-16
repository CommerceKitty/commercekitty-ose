<?php
/**
 * @see https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html
 */

namespace App\DataFixtures;

use App\Entity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @TODO Add References
 */
class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getTemplates() as $tmpl) {
            $entity = (new Entity\User())
                ->setEmail($tmpl['email'])
                ->setRoles($tmpl['roles'])
            ;
            $entity->setPassword(
                $this->passwordEncoder->encodePassword(
                    $entity,
                    $tmpl['plain_password']
                )
            );
            $manager->persist($entity);
        }

        $manager->flush();
    }

    private function getTemplates(): \Generator
    {
        yield [
            'email'          => 'kitty@commercekitty.com',
            'roles'          => ['ROLE_USER'],
            'plain_password' => 'password123',
        ];
        yield [
            'email'          => 'demo@commercekitty.com',
            'roles'          => ['ROLE_USER'],
            'plain_password' => 'password123',
        ];
    }
}
