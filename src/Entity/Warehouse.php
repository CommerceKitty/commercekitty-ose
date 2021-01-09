<?php

namespace CommerceKitty\Entity;

use CommerceKitty\Model;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CommerceKitty\Repository\WarehouseRepository")
 * @ORM\Table(name="warehouses")
 */
class Warehouse extends Model\Warehouse
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;
}
