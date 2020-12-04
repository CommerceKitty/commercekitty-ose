<?php

namespace CommerceKitty\Entity;

use CommerceKitty\Model;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="woocommerce_channels")
 */
class WoocommerceChannel extends Channel implements Model\WoocommerceChannelInterface
{
    use Model\WoocommerceChannelTrait;

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
    protected $consumerKey;

    /**
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $consumerSecret;
}
