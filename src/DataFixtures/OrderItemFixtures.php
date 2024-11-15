<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\OrderItem;
use App\DataFixtures\OrderFixtures;
use App\DataFixtures\ProductFixtures;

class OrderItemFixtures extends Fixture implements DependentFixtureInterface
{
    private const ORDER_ITEM_REF_PREFIX = 'order_item_';

    public function load(ObjectManager $manager): void
    {
        $orderItemsData = [
            [
                'quantity' => 2,
                'product_price' => 9000.00,
                'order_ref' => 'order_1',
                'product_ref' => 'product_1'
            ],
            [
                'quantity' => 1,
                'product_price' => 9000.00,
                'order_ref' => 'order_1',
                'product_ref' => 'product_1'
            ],
            [
                'quantity' => 3,
                'product_price' => 7500.00,
                'order_ref' => 'order_2',
                'product_ref' => 'product_2'
            ],
            [
                'quantity' => 1,
                'product_price' => 15000.00,
                'order_ref' => 'order_3',
                'product_ref' => 'product_3'
            ],
            [
                'quantity' => 1,
                'product_price' => 15000.00,
                'order_ref' => 'order_4',
                'product_ref' => 'product_3'
            ],
            [
                'quantity' => 1,
                'product_price' => 10000.00,
                'order_ref' => 'order_4',
                'product_ref' => 'product_2'
            ],
            [
                'quantity' => 2,
                'product_price' => 9500.00,
                'order_ref' => 'order_5',
                'product_ref' => 'product_1'
            ],
            [
                'quantity' => 1,
                'product_price' => 22000.00,
                'order_ref' => 'order_6',
                'product_ref' => 'product_5'
            ],
            [
                'quantity' => 2,
                'product_price' => 3000.00,
                'order_ref' => 'order_7',
                'product_ref' => 'product_6'
            ],
            [
                'quantity' => 1,
                'product_price' => 3000.00,
                'order_ref' => 'order_7',
                'product_ref' => 'product_6'
            ],
            [
                'quantity' => 2,
                'product_price' => 14000.00,
                'order_ref' => 'order_8',
                'product_ref' => 'product_7'
            ],
            [
                'quantity' => 1,
                'product_price' => 2000.00,
                'order_ref' => 'order_9',
                'product_ref' => 'product_8'
            ],
            [
                'quantity' => 3,
                'product_price' => 2000.00,
                'order_ref' => 'order_9',
                'product_ref' => 'product_8'
            ],
            [
                'quantity' => 1,
                'product_price' => 13000.00,
                'order_ref' => 'order_10',
                'product_ref' => 'product_9'
            ],
            [
                'quantity' => 2,
                'product_price' => 13000.00,
                'order_ref' => 'order_10',
                'product_ref' => 'product_9'
            ],
            [
                'quantity' => 1,
                'product_price' => 50000.00,
                'order_ref' => 'order_11',
                'product_ref' => 'product_10'
            ],
            [
                'quantity' => 2,
                'product_price' => 15000.00,
                'order_ref' => 'order_12',
                'product_ref' => 'product_3'
            ],
            [
                'quantity' => 1,
                'product_price' => 18000.00,
                'order_ref' => 'order_13',
                'product_ref' => 'product_4'
            ],
            [
                'quantity' => 1,
                'product_price' => 15000.00,
                'order_ref' => 'order_14',
                'product_ref' => 'product_3'
            ],
            [
                'quantity' => 3,
                'product_price' => 5000.00,
                'order_ref' => 'order_15',
                'product_ref' => 'product_6'
            ],
            [
                'quantity' => 2,
                'product_price' => 45000.00,
                'order_ref' => 'order_16',
                'product_ref' => 'product_10'
            ],
            [
                'quantity' => 1,
                'product_price' => 14000.00,
                'order_ref' => 'order_17',
                'product_ref' => 'product_7'
            ],
            [
                'quantity' => 1,
                'product_price' => 3500.00,
                'order_ref' => 'order_18',
                'product_ref' => 'product_2'
            ],
            [
                'quantity' => 2,
                'product_price' => 4500.00,
                'order_ref' => 'order_19',
                'product_ref' => 'product_5'
            ],
            [
                'quantity' => 1,
                'product_price' => 8000.00,
                'order_ref' => 'order_20',
                'product_ref' => 'product_2'
            ]
        ];

        foreach ($orderItemsData as $key => $orderItemData) {
            $orderItem = $this->createOrderItem($orderItemData);
            $manager->persist($orderItem);
            $this->addReference(self::ORDER_ITEM_REF_PREFIX.($key + 1), $orderItem);
        }

        $manager->flush();
    }

    private function createOrderItem(array $data): OrderItem
    {
        $orderItem = new OrderItem();
        $orderItem->setQuantity($data['quantity']);
        $orderItem->setProductPrice($data['product_price']);
        $orderItem->setOrder($this->getReference($data['order_ref']));
        $orderItem->setProduct($this->getReference($data['product_ref']));

        return $orderItem;
    }

    public function getDependencies()
    {
        return [
            OrderFixtures::class,
            ProductFixtures::class
        ];
    }
}
