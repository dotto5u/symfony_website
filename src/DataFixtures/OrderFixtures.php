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
                'created_at' => new \DateTime('2024-09-05'),
                'status' => OrderStatus::Delivered,
                'user_ref' => 'user_1',
                'order_items_ref' => ['order_item_1', 'order_item_2']
            ],
            [
                'reference' => 'ORD-0002',
                'created_at' => new \DateTime('2024-09-07'),
                'status' => OrderStatus::Shipped,
                'user_ref' => 'user_1',
                'order_items_ref' => ['order_item_3']
            ],
            [
                'reference' => 'ORD-0003',
                'created_at' => new \DateTime('2024-09-10'),
                'status' => OrderStatus::InPreparation,
                'user_ref' => 'user_2',
                'order_items_ref' => ['order_item_4', 'order_item_5']
            ],
            [
                'reference' => 'ORD-0004',
                'created_at' => new \DateTime('2024-09-15'),
                'status' => OrderStatus::Canceled,
                'user_ref' => 'user_3',
                'order_items_ref' => ['order_item_6']
            ],
            [
                'reference' => 'ORD-0005',
                'created_at' => new \DateTime('2024-09-20'),
                'status' => OrderStatus::Delivered,
                'user_ref' => 'user_3',
                'order_items_ref' => ['order_item_7', 'order_item_8']
            ],
            [
                'reference' => 'ORD-0006',
                'created_at' => new \DateTime('2024-09-02'),
                'status' => OrderStatus::Shipped,
                'user_ref' => 'user_4',
                'order_items_ref' => ['order_item_9']
            ],
            [
                'reference' => 'ORD-0007',
                'created_at' => new \DateTime('2024-09-25'),
                'status' => OrderStatus::InPreparation,
                'user_ref' => 'user_4',
                'order_items_ref' => ['order_item_10', 'order_item_11']
            ],
            [
                'reference' => 'ORD-0008',
                'created_at' => new \DateTime('2024-09-28'),
                'status' => OrderStatus::Delivered,
                'user_ref' => 'user_1',
                'order_items_ref' => ['order_item_12']
            ],
            [
                'reference' => 'ORD-0009',
                'created_at' => new \DateTime('2024-09-30'),
                'status' => OrderStatus::Shipped,
                'user_ref' => 'user_2',
                'order_items_ref' => ['order_item_13', 'order_item_14']
            ],
            [
                'reference' => 'ORD-00010',
                'created_at' => new \DateTime('2024-09-01'),
                'status' => OrderStatus::Delivered,
                'user_ref' => 'user_3',
                'order_items_ref' => ['order_item_15', 'order_item_16']
            ],
            [
                'reference' => 'ORD-00011',
                'created_at' => new \DateTime('2024-10-05'),
                'status' => OrderStatus::InPreparation,
                'user_ref' => 'user_4',
                'order_items_ref' => ['order_item_17']
            ]
        ];

        foreach ($ordersData as $key => $orderData)
        {
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

        foreach ($data['order_items_ref'] as $orderItemRef) {
            $order->addOrderItem($this->getReference($orderItemRef));
        }

        return $order;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}