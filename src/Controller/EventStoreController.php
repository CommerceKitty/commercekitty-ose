<?php declare(strict_types=1);

namespace CommerceKitty\Controller;

use CommerceKitty\Entity;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EventStoreController extends AbstractController
{
    /**
     */
    public function index()
    {
        return $this->render('event_store/index.html.twig');
    }

    /**
     */
    public function warehouseIndex(Request $request, PaginatorInterface $paginator)
    {
        $builder = $this->getDoctrine()->getRepository(Entity\Warehouse\WarehouseEventStore::class)
            ->createQueryBuilder('e')
            ->orderBy('e.createdAt', 'DESC')
        ;

        $pager = $paginator->paginate($builder, $request->query->getInt('page', 1), $request->query->getInt('limit', 10));

        return $this->render('event_store/warehouse/index.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     */
    public function warehouseShow($id)
    {
        $entity = $this->getDoctrine()->getRepository(Entity\Warehouse\WarehouseEventStore::class)
            ->find($id);

        return $this->render('event_store/warehouse/show.html.twig', [
            'entity' => $entity,
        ]);
    }

    /**
     */
    public function warehouseDelete($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $entity  = $manager->getRepository(Entity\Warehouse\WarehouseEventStore::class)
            ->find($id);
        $manager->remove($entity);
        $manager->flush();

        return $this->redirectToRoute('event_store_warehouse_index');
    }
}
