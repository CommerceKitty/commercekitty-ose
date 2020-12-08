<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Shopify;

use CommerceKitty\Entity;
use CommerceKitty\Factory\ShopifyClientFactory;
//use CommerceKitty\Message\Command\CreateOrderCommand;
use CommerceKitty\Message\Command\Shopify\ImportOrdersCommand;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ImportOrdersHandler implements CommandHandlerInterface
{
    private $manager;
    private $factory;
    private $commandBus;

    /**
     */
    public function __construct(EntityManagerInterface $manager, ShopifyClientFactory $factory, MessageBusInterface $commandBus)
    {
        $this->manager    = $manager;
        $this->factory    = $factory;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ImportOrdersCommand $message
     *
     * @return void
     */
    public function __invoke(ImportOrdersCommand $message): void
    {
        $entity    = $this->manager->getRepository(Entity\Channel::class)->find($message->getChannelId());
        $lastOrder = $this->manager->getRepository(Entity\Order::class)
            ->createQueryBuilder('o')
            ->andWhere('o.channel = :channel')
            ->orderBy('o.createdAt', 'DESC')
            ->setParameter('channel', $entity)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
        $parameters = [
            'limit' => 250,
        ];
        if ($lastOrder) {
            $parameters['created_at_min'] = $lastOrder->getCreatedAt()->format(\DateTimeInterface::ISO8601);
        }

        $client = $this->factory->createShopifyClient($entity);

        $response = $client->getResource('GET', '/orders.json', $parameters);
        $content  = $response->getContent();
        $orders   = json_decode($content, true)['orders'];

        foreach ($orders as $order) {
            //> If order already exists, skip to the next one
            $orderEntity = $this->manager->getRepository(Entity\Order::class)
                ->createQueryBuilder('o')
                ->andWhere('o.channel = :channel AND o.fid = :fid')
                ->setParameters([
                    'channel' => $entity,
                    'fid'     => $order['id'],
                ])
                ->getQuery()
                ->getOneOrNullResult()
            ;
            if ($orderEntity) {
                continue;
            }
            //<

            $orderEntity = (new Entity\Order())
                ->setChannel($entity)
                ->setFid((string) $order['id'])
                ->setDispalyId($order['name'])
                ->setCreatedAt(new \DateTime($order['created_at']))
            ;

            // @todo Find/Create Customer

            foreach ($order['line_items'] as $lineItem) {
                //> @todo If product not found, we need to import it
                //> @todo Ignore sku case?
                $productEntity = $this->manager->getRepository(Entity\Product::class)
                    ->findOneBy(['sku' => $lineItem['sku']]);
                if (null === $productEntity) {
                    continue;
                }
                //<
                $orderItemEntity = (new Entity\OrderItem())
                    ->setProduct($productEntity)
                    ->setFid((string) $lineItem['id'])
                    ->setQuantity($lineItem['quantity'])
                ;
                $orderEntity->addOrderItem($orderItemEntity);
            }

            $this->manager->persist($orderEntity);
        }

        $this->manager->flush();
    }
}
