<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Order;
use App\Entity\OrderQuantity;

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

    public function insertOrderQuantity(OrderQuantity $orderQuantity): void
    {
        $this->_em->persist($orderQuantity);
        $this->_em->flush();
    }
}
