<?php

namespace App\Repository;

use App\Entity\Order;
use App\Enum\OrderStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function getAll(bool $asQuery = false): array|Query
    {
        $query = $this->createQueryBuilder('o')
            ->orderBy('o.reference', 'ASC')
            ->getQuery();

        return $asQuery ? $query : $query->getResult();
    }

    public function getLastFiveOrders(): array
    {
        $result = $this->createQueryBuilder('o')
            ->orderBy('o.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function getSalesOverLastTwelveMonths(): array
    {
        $startDate = (new \DateTime('first day of this month'))->modify('-12 months');
        $endDate = new \DateTime('first day of this month');

        $result = $this->createQueryBuilder('o')
            ->select(
                'YEAR(o.createdAt) AS year',
                'LPAD(MONTH(o.createdAt), 2, 0) AS month',
                'SUM(oi.quantity * oi.productPrice) AS total'
            )
            ->join('o.orderItems', 'oi')
            ->where('o.status != :canceled')
            ->andWhere('o.createdAt >= :startDate')
            ->andWhere('o.createdAt < :endDate')
            ->groupBy('year, month')
            ->orderBy('year', 'ASC')
            ->addOrderBy('month', 'ASC')
            ->setParameter('canceled', OrderStatus::CANCELED->value)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();

        $interval = new \DateInterval('P1M');
        $period = new \DatePeriod($startDate, $interval, $endDate);

        $monthlySales = array_reduce($result, function ($sales, $sale) {
            $key = $sale['year'].'-'.$sale['month'];
            $sales[$key] = $sale['total'];
            return $sales;
        }, []);

        foreach ($period as $date) {
            $year = $date->format('Y');
            $month = $date->format('m');
            $key = $year.'-'.$month;

            $sales[] =
            [
                'year' => $year,
                'month' => $month,
                'total' => $monthlySales[$key] ?? 0,
            ];
        }

        return $sales;
    }
}
