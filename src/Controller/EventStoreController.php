<?php declare(strict_types=1);

namespace CommerceKitty\Controller;

use CommerceKitty\Entity;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\u;

class EventStoreController extends AbstractController
{
    /**
     */
    public function index(): Response
    {
        return $this->render('event_store/index.html.twig');
    }

    /**
     */
    public function aggregateIndex(Request $request, PaginatorInterface $paginator, string $aggregate): Response
    {
        $aggregate = u($aggregate)->lower()->camel()->title()->toString(); // ie: order_line_item => OrderLineItem

        $fqcn    = 'CommerceKitty\\Entity\\'.$aggregate.'\\'.$aggregate.'EventStore'; // Consider dropping the second $aggregate
        $builder = $this->getDoctrine()->getRepository($fqcn)
            ->createQueryBuilder('e')
            ->orderBy('e.createdAt', 'DESC')
        ;

        $pager = $paginator->paginate($builder, $request->query->getInt('page', 1), $request->query->getInt('limit', 10));

        return $this->render('event_store/'.u($aggregate)->snake()->toString().'/index.html.twig', [
            'pager'     => $pager,
            'aggregate' => u($aggregate)->snake()->toString(),
        ]);
    }

    /**
     */
    public function aggregateShow(string $aggregate, string $id): Response
    {
        $aggregate = u($aggregate)->lower()->camel()->title()->toString(); // ie: order_line_item => OrderLineItem

        $fqcn   = 'CommerceKitty\\Entity\\'.$aggregate.'\\'.$aggregate.'EventStore'; // Consider dropping the second $aggregate
        $entity = $this->getDoctrine()->getRepository($fqcn)->find($id);

        return $this->render('event_store/'.u($aggregate)->snake()->toString().'/show.html.twig', [
            'entity'    => $entity,
            'aggregate' => u($aggregate)->snake()->toString(),
        ]);
    }

    /**
     */
    public function aggregateDelete(string $aggregate, string $id): Response
    {
        $aggregate = u($aggregate)->lower()->camel()->title()->toString(); // ie: order_line_item => OrderLineItem

        $fqcn    = 'CommerceKitty\\Entity\\'.$aggregate.'\\'.$aggregate.'EventStore'; // Consider dropping the second $aggregate
        $manager = $this->getDoctrine()->getManager();
        $entity  = $manager->getRepository($fqcn)->find($id);

        $manager->remove($entity);
        $manager->flush();

        $aggregate = u($aggregate)->snake()->toString();

        return $this->redirectToRoute('event_store_aggregate_index', [
            'aggregate' => u($aggregate)->snake()->toString(),
        ]);
    }
}
