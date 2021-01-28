<?php

namespace CommerceKitty\Entity\Vendor;

use CommerceKitty\Entity\EventStore;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *   name="vendor_event_store",
 *   indexes={
 *     @ORM\Index(name="idx_01EWH7V78XKE7T81Q27YYW628K", columns={"aggregate_root_id"}, options={"where":"(aggregate_root_id IS NOT NULL)"})
 *   }
 * )
 * @ORM\Entity()
 */
class VendorEventStore extends EventStore
{
}
