<?php

namespace App\Entity;

use App\Model;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="products")
 */
class Product extends Model\Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
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
     * @todo Unique constraint
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
