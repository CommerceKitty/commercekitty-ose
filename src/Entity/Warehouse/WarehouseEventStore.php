<?php

namespace CommerceKitty\Entity\Warehouse;

use CommerceKitty\Entity\EventStore;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *   name="warehouse_event_store",
 *   indexes={
 *     @ORM\Index(name="idx_01ETVJFHVDAMPJ5KM5R8Q5WYBK", columns={"aggregate_root_id"}, options={"where":"(aggregate_root_id IS NOT NULL)"})
 *   }
 * )
 * @ORM\Entity()
 */
class WarehouseEventStore extends EventStore
{
}
