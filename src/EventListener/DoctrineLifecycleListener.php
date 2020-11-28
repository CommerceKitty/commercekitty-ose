<?php declare(strict_types=1);

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use function Symfony\Component\String\u;

/**
 * This will fire off events using the correct naming format
 *
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/events.html
 */
class DoctrineLifecycleListener
{
    /**
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Pass in an Entity and it will return the snake case of that entity
     *
     * Example: OrderItem => order_item
     *
     * @param Object $entity
     *
     * @return string
     */
    private function snake($entity): string
    {
        $class = get_class($entity);
        $parts = explode('\\', $class);
        $name  = array_pop($parts);

        return (string) u($name)->snake();
    }

    /**
     */
    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity    = $args->getEntity();
        $eventName = $this->snake($entity).'.pre_remove';
        $this->dispatcher->dispatch(new GenericEvent($entity, ['args' => $args]), $eventName);
    }

    /**
     */
    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity    = $args->getEntity();
        $eventName = $this->snake($entity).'.post_remove';
        $this->dispatcher->dispatch(new GenericEvent($entity, ['args' => $args]), $eventName);
    }

    /**
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity    = $args->getEntity();
        $eventName = $this->snake($entity).'.pre_create';
        $this->dispatcher->dispatch(new GenericEvent($entity, ['args' => $args]), $eventName);
    }

    /**
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity    = $args->getEntity();
        $eventName = $this->snake($entity).'.post_create';
        $this->dispatcher->dispatch(new GenericEvent($entity, ['args' => $args]), $eventName);
    }

    /**
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity    = $args->getEntity();
        $eventName = $this->snake($entity).'.pre_update';
        $this->dispatcher->dispatch(new GenericEvent($entity, ['args' => $args]), $eventName);
    }

    /**
     */
    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity    = $args->getEntity();
        $eventName = $this->snake($entity).'.post_update';
        $this->dispatcher->dispatch(new GenericEvent($entity, ['args' => $args]), $eventName);
    }
}
