<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Enum\OrderStatus;
use App\Entity\Order;
use App\DataFixtures\UserFixtures;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    private const ORDER_REF_PREFIX = 'order_';

    public function load(ObjectManager $manager): void
    {
        $ordersData = [
            [
                'reference' => 'ORD-0001',
                'created_at' => new \DateTime('2023-12-05'),
                'status' => OrderStatus::Delivered,
                'user_ref' => 'user_1'
            ],
            [
                'reference' => 'ORD-0002',
                'created_at' => new \DateTime('2024-01-10'),
                'status' => OrderStatus::Shipped,
                'user_ref' => 'user_1'
            ],
            [
                'reference' => 'ORD-0003',
                'created_at' => new \DateTime('2024-01-15'),
                'status' => OrderStatus::InPreparation,
                'user_ref' => 'user_2'
            ],
            [
                'reference' => 'ORD-0004',
                'created_at' => new \DateTime('2024-02-02'),
                'status' => OrderStatus::Delivered,
                'user_ref' => 'user_2'
            ],
            [
                'reference' => 'ORD-0005',
                'created_at' => new \DateTime('2024-02-14'),
                'status' => OrderStatus::Shipped,
                'user_ref' => 'user_3'
            ],
            [
                'reference' => 'ORD-0006',
                'created_at' => new \DateTime('2024-03-03'),
                'status' => OrderStatus::InPreparation,
                'user_ref' => 'user_3'
            ],
            [
                'reference' => 'ORD-0007',
                'created_at' => new \DateTime('2024-03-12'),
                'status' => OrderStatus::Shipped,
                'user_ref' => 'user_4'
            ],
            [
                'reference' => 'ORD-0008',
                'created_at' => new \DateTime('2024-03-21'),
                'status' => OrderStatus::Delivered,
                'user_ref' => 'user_4'
            ],
            [
                'reference' => 'ORD-0009',
                'created_at' => new \DateTime('2024-04-02'),
                'status' => OrderStatus::InPreparation,
                'user_ref' => 'user_1'
            ],
            [
                'reference' => 'ORD-0010',
                'created_at' => new \DateTime('2024-04-20'),
                'status' => OrderStatus::Delivered,
                'user_ref' => 'user_1'
            ],
            [
                'reference' => 'ORD-0011',
                'created_at' => new \DateTime('2024-05-10'),
                'status' => OrderStatus::Shipped,
                'user_ref' => 'user_4'
            ],
            [
                'reference' => 'ORD-0012',
                'created_at' => new \DateTime('2024-05-25'),
                'status' => OrderStatus::Delivered,
                'user_ref' => 'user_2'
            ],
            [
                'reference' => 'ORD-0013',
                'created_at' => new \DateTime('2024-06-06'),
                'status' => OrderStatus::Shipped,
                'user_ref' => 'user_3'
            ],
            [
                'reference' => 'ORD-0014',
                'created_at' => new \DateTime('2024-06-15'),
                'status' => OrderStatus::InPreparation,
                'user_ref' => 'user_4'
            ],
            [
                'reference' => 'ORD-0015',
                'created_at' => new \DateTime('2024-07-04'),
                'status' => OrderStatus::Delivered,
                'user_ref' => 'user_1'
            ],
            [
                'reference' => 'ORD-0016',
                'created_at' => new \DateTime('2024-08-10'),
                'status' => OrderStatus::Shipped,
                'user_ref' => 'user_4'
            ],
            [
                'reference' => 'ORD-0017',
                'created_at' => new \DateTime('2024-08-20'),
                'status' => OrderStatus::InPreparation,
                'user_ref' => 'user_2'
            ],
            [
                'reference' => 'ORD-0018',
                'created_at' => new \DateTime('2024-09-05'),
                'status' => OrderStatus::Delivered,
                'user_ref' => 'user_3'
            ],
            [
                'reference' => 'ORD-0019',
                'created_at' => new \DateTime('2024-10-05'),
                'status' => OrderStatus::Shipped,
                'user_ref' => 'user_3'
            ],
            [
                'reference' => 'ORD-0020',
                'created_at' => new \DateTime('2024-11-01'),
                'status' => OrderStatus::Delivered,
                'user_ref' => 'user_2'
            ]
        ];

        foreach ($ordersData as $key => $orderData) {
            $order = $this->createOrder($orderData);
            $manager->persist($order);
            $this->addReference(self::ORDER_REF_PREFIX.($key + 1), $order);
        }

        $manager->flush();
    }

    private function createOrder(array $data): Order
    {
        $order = new Order();
        $order->setReference($data['reference']);
        $order->setCreatedAt($data['created_at']);
        $order->setStatus($data['status']);
        $order->setUser($this->getReference($data['user_ref']));

        return $order;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
