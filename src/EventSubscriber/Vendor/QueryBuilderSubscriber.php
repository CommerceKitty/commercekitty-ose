<?php

namespace CommerceKitty\EventSubscriber\Vendor;

use CommerceKitty\Event\QueryBuilderEventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QueryBuilderSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'query_builder.vendor.build' => [
                ['onBuildQueryBuilder', -255],
            ]
        ];
    }

    /**
     */
    public function onBuildQueryBuilder(QueryBuilderEventInterface $event): void
    {
        $event->getQueryBuilder()->orderBy('e.name', 'ASC');
    }
}
