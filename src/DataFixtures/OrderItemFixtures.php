<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\OrderItem;
use App\Entity\Product;

class OrderItemFixtures extends Fixture implements DependentFixtureInterface
{
    private const ORDER_ITEM_REF_PREFIX = 'order_item_';

    public function load(ObjectManager $manager): void
    {
        $orderItemsData = [
            [
                'quantity' => 2,
                'productPrice' => 9000.00,
                'order' => 'order_1',
                'product' => 'product_1'
            ],
            [
                'quantity' => 1,
                'productPrice' => 9000.00,
                'order' => 'order_1',
                'product' => 'product_1'
            ],
            [
                'quantity' => 3,
                'productPrice' => 7500.00,
                'order' => 'order_2',
                'product' => 'product_2'
            ],
            [
                'quantity' => 1,
                'productPrice' => 15000.00,
                'order' => 'order_3',
                'product' => 'product_3'
            ],
            [
                'quantity' => 2,
                'productPrice' => 15000.00,
                'order' => 'order_3',
                'product' => 'product_3'
            ],
            [
                'quantity' => 1,
                'productPrice' => 15000.00,
                'order' => 'order_4',
                'product' => 'product_3'
            ],
            [
                'quantity' => 1,
                'productPrice' => 1100.00,
                'order' => 'order_5',
                'product' => 'product_4'
            ],
            [
                'quantity' => 2,
                'productPrice' => 1100.00,
                'order' => 'order_5',
                'product' => 'product_4'
            ],
            [
                'quantity' => 1,
                'productPrice' => 2200.00,
                'order' => 'order_6',
                'product' => 'product_5'
            ],
            [
                'quantity' => 2,
                'productPrice' => 3000.00,
                'order' => 'order_7',
                'product' => 'product_6'
            ],
            [
                'quantity' => 1,
                'productPrice' => 3000.00,
                'order' => 'order_7',
                'product' => 'product_6'
            ],
            [
                'quantity' => 2,
                'productPrice' => 14000.00,
                'order' => 'order_8',
                'product' => 'product_7'
            ],
            [
                'quantity' => 1,
                'productPrice' => 2000.00,
                'order' => 'order_9',
                'product' => 'product_8'
            ],
            [
                'quantity' => 3,
                'productPrice' => 2000.00,
                'order' => 'order_9',
                'product' => 'product_8'
            ],
            [
                'quantity' => 1,
                'productPrice' => 13000.00,
                'order' => 'order_10',
                'product' => 'product_9'
            ],
            [
                'quantity' => 2,
                'productPrice' => '13000.00',
                'order' => 'order_10',
                'product' => 'product_9'
            ],
            [
                'quantity' => 1,
                'productPrice' => 50000.00,
                'order' => 'order_11',
                'product' => 'product_10'
            ]
        ];                         

        foreach ($orderItemsData as $key => $orderItemData)
        {
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
        $orderItem->setProductPrice($data['productPrice']);
        $orderItem->setOrder($data['order']);
        $orderItem->setProduct($data['product']);

        return $orderItem;
    }

    public function getDependencies()
    {
        return [
            
        ];
    }
}