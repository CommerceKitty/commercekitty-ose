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
        $entity = $this->manager->getRepository(Entity\Channel::class)->find($message->getChannelId());
        $client = $this->factory->createShopifyClient($entity);

        // @todo created_at_min = last time we pulled orders
        $response = $client->getResource('GET', '/orders.json', ['limit' => 250, 'created_at_min' => '2014-04-25T16:15:47-04:00']);
        $content  = $response->getContent();
        $orders   = json_decode($content, true)['orders'];

        dd($orders);
        foreach ($orders as $order) {
        }
    }
}
