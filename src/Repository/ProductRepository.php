<?php

namespace App\Repository;

use App\Entity\Product;
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

    public function getProductCountByCategory(bool $asQuery = false): array|Query
    {
        $query = $this->createQueryBuilder('p')
            ->select('c.name AS categoryName', 'COUNT(p.id) AS productCount')
            ->join('p.categories', 'c')
            ->groupBy('c.id')
            ->orderBy('productCount', 'DESC')
            ->getQuery();

        return $asQuery ? $query : $query->getResult();
    }
}
