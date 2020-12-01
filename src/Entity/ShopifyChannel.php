<?php

namespace App\Entity;

use App\Model;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="shopify_channels")
 */
class ShopifyChannel extends Channel implements Model\ShopifyChannelInterface
{
    use Model\ShopifyChannelTrait;

    /**
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     * @Assert\Url()
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $host;

    /**
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $apiKey;

    /**
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $password;
}
