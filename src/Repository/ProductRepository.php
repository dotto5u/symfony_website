<?php

namespace App\Repository;

use App\Entity\Product;
use App\Enum\ProductStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getAll(bool $asQuery = false): array|Query
    {
        $query = $this->createQueryBuilder('p')
            ->getQuery();

        return $asQuery ? $query : $query->getResult();
    }

    public function getProductCountByCategory(): array
    {
        $result = $this->createQueryBuilder('p')
            ->select('c.name AS categoryName', 'COUNT(p.id) AS productCount')
            ->join('p.categories', 'c')
            ->groupBy('c.id')
            ->orderBy('productCount', 'DESC')
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function getAvailabilityRatio(): array
    {
        $result = $this->createQueryBuilder('p')
            ->select(
                'SUM(CASE WHEN p.status = :available THEN 1 ELSE 0 END) AS available',
                'SUM(CASE WHEN p.status = :preOrder THEN 1 ELSE 0 END) AS preOrder',
                'SUM(CASE WHEN p.status = :soldOut THEN 1 ELSE 0 END) AS soldOut',
                'COUNT(p.id) AS total'
            )
            ->setParameter('available', ProductStatus::Available->value)
            ->setParameter('preOrder', ProductStatus::PreOrder->value)
            ->setParameter('soldOut', ProductStatus::SoldOut->value)
            ->getQuery()
            ->getSingleResult();

        $total = $result['total'] ?: 1;

        return [
            'available' => ($result['available'] / $total) * 100,
            'pre_order' => ($result['preOrder'] / $total) * 100,
            'sold_out' => ($result['soldOut'] / $total) * 100,
        ];
    }
}
