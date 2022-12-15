<?php

use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    public function findOrder(int $id)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('o')
            ->from(Order::class, 'o')
            ->where('o.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findOrderStatus(int $id)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('o.orderStatus')
            ->from(Order::class, 'o')
            ->where('o.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findOrderDate(int $id)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('o.orderDate')
            ->from(Order::class, 'o')
            ->where('o.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findOrderShipping(int $id)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('o.shippingCost')
            ->from(Order::class, 'o')
            ->where('o.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findOrderTotal(int $id)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('o.orderTotal')
            ->from(Order::class, 'o')
            ->where('o.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findOrderManga(int $id)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('m')
            ->from(Manga::class, 'm')
            ->join('m.order', 'o')
            ->where('o.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findOrderUser(int $id)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('u')
            ->from(User::class, 'u')
            ->join('u.order', 'o')
            ->where('o.id = :id')
            ->setParameter('id', $id);
    }
}
