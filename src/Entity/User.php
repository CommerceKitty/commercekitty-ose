<?php

namespace CommerceKitty\Entity;

use CommerceKitty\Model;
use CommerceKitty\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 * @UniqueEntity("email")
 */
class User extends Model\User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @Assert\Email
     * @Assert\Length(max=180)
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @UserPassword(
     *   message="Invalid Password",
     *   groups={"change_password"}
     * )
     */
    protected $currentPassword;
}
