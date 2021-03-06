<?php

namespace CommerceKitty\Entity;

use CommerceKitty\Model;
use CommerceKitty\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="products")
 * @UniqueEntity("sku")
 */
class Product extends Model\Product
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @todo Unique database constraint
     *
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255)
     */
    protected $sku;

    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255)
     */
    protected $type;
}
