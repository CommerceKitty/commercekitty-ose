<?php

namespace CommerceKitty\Entity;

use CommerceKitty\Model;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CommerceKitty\Repository\InventoryRepository")
 * @ORM\Table(name="inventory")
 */
class Inventory extends Model\Inventory
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Warehouse")
     * @ORM\JoinColumn(name="warehouse_id", referencedColumnName="id", nullable=false)
     */
    protected $warehouse;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    protected $product;

    /**
     * @ORM\Column(type="integer", options={"default"=0}, nullable=true)
     */
    protected $quantity;
}
