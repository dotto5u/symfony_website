<?php

namespace App\Repository;

use App\Entity\CreditCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CreditCard>
 */
class CreditCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreditCard::class);
    }

    public function getByUser(int $userId): array
    {
        return $this->createQueryBuilder('cc')
            ->innerJoin('cc.users', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }
}
