<?php declare(strict_types=1);

namespace CommerceKitty\MessageHandler\Command\Shopify;

use CommerceKitty\Entity;
use CommerceKitty\Factory\ShopifyClientFactory;
use CommerceKitty\Message\Command\CreateProductCommand;
use CommerceKitty\Message\Command\Shopify\ImportProductsCommand;
use CommerceKitty\MessageHandler\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ImportProductsHandler implements CommandHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     */
    private $factory;

    private $commandBus;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager, ShopifyClientFactory $factory, MessageBusInterface $commandBus)
    {
        $this->manager    = $manager;
        $this->factory    = $factory;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ImportProductsCommand $message
     *
     * @return void
     */
    public function __invoke(ImportProductsCommand $message): void
    {
        $entity   = $this->manager->getRepository(Entity\Channel::class)->find($message->getChannelId());
        $client   = $this->factory->createShopifyClient($entity);
        $response = $client->getResource('GET', '/products.json');
        $content  = $response->getContent();
        $products = json_decode($content, true)['products'];

        foreach ($products as $product) {
            //dump($product);
            foreach ($product['variants'] as $variant) {
                //dump($variant);
                $name    = $product['title'].' '.$variant['title'];
                $command = (new CreateProductCommand('simple', $name, $variant['sku']))
                    ->setChannelId($entity->getId());
                $this->commandBus->dispatch($command);
            }
        }
    }
}
