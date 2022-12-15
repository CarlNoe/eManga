<?php

use Doctrine\ORM\EntityRepository;
use App\Entity\Order;

class OrderQuantityRepository extends EntityRepository
{
    public function findQuantityByOrder(Order $order): array
    {
        $qb = $this->createQueryBuilder('oq');
        $qb->select('oq.quantity, oq.manga')
            ->where('oq.order = :order')
            ->setParameter('order', $order);

        return $qb->getQuery()->getResult();
    }
}
