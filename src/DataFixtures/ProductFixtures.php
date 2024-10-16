<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Enum\ProductStatus;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\OrderItems;
use App\Entity\Image;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{   
    private const PRODUCT_REF_PREFIX = 'product_';

    public function load(ObjectManager $manager): void
    {
        $productsData = [
            [
                'name' => 'Yamaha U1',
                'price' => 9000.00,
                'description' => 'Un piano droit avec une sonorité exceptionnelle, parfait pour les musiciens de tous niveaux.',
                'stock' => 5,
                'status' => ProductStatus::Available,
                'categories' => ['category_1', 'category_5'],
                'orderItems' => ['order_item_1', 'order_item_2'],
                'image' => 'image_1'
            ],
            [
                'name' => 'Kawai K300',
                'price' => 7500.00,
                'description' => 'Le K300 est un piano droit reconnu pour sa fiabilité et sa qualité sonore supérieure.',
                'stock' => 3,
                'status' => ProductStatus::PreOrder,
                'categories' => ['category_1'],
                'orderItems' => ['order_item_3'],
                'image' => 'image_2'
            ],
            [
                'name' => 'Steinway & Sons Model B',
                'price' => 15000.00,
                'description' => 'Un piano à queue pour les professionnels, offrant un son riche et une qualité de fabrication inégalée.',
                'stock' => 4,
                'status' => ProductStatus::Available,
                'categories' => ['category_2', 'category_5'],
                'orderItems' => ['order_item_4', 'order_item_5', 'order_item_6'],
                'image' => 'image_3'
            ],
            [
                'name' => 'Casio Privia PX-870',
                'price' => 1100.00,
                'description' => 'Un piano numérique compact avec un toucher réaliste et une variété de sons.',
                'stock' => 10,
                'status' => ProductStatus::Available,
                'categories' => ['category_3'],
                'orderItems' => ['order_item_7', 'order_item_8'],
                'image' => 'image_4'
            ],
            [
                'name' => 'Roland HP704',
                'price' => 2200.00,
                'description' => 'Un piano numérique avec un son riche et une mécanique de touches immersive.',
                'stock' => 0,
                'status' => ProductStatus::SoldOut,
                'categories' => ['category_3', 'category_5'],
                'orderItems' => ['order_item_9'],
                'image' => 'image_5'
            ],
            [
                'name' => 'Yamaha CLP-775',
                'price' => 3000.00,
                'description' => 'Un piano numérique haut de gamme pour les pianistes exigeants.',
                'stock' => 4,
                'status' => ProductStatus::Available,
                'categories' => ['category_3'],
                'orderItems' => ['order_item_10', 'order_item_11'],
                'image' => 'image_6'
            ],
            [
                'name' => 'Fazioli F278',
                'price' => 14000.00,
                'description' => 'Un piano à queue de concert réputé pour sa clarté et sa richesse sonore.',
                'stock' => 2,
                'status' => ProductStatus::PreOrder,
                'categories' => ['category_2', 'category_5'],
                'orderItems' => ['order_item_12'],
                'image' => 'image_7'
            ],
            [
                'name' => 'Korg Grandstage 88',
                'price' => 2000.00,
                'description' => 'Un piano de scène avec une large gamme de sons et une excellente jouabilité.',
                'stock' => 6,
                'status' => ProductStatus::Available,
                'categories' => ['category_4'],
                'orderItems' => ['order_item_13', 'order_item_14'],
                'image' => 'image_8'
            ],
            [
                'name' => 'Blüthner Model 1',
                'price' => 13000.00,
                'description' => 'Un piano à queue élégant offrant une qualité sonore exceptionnelle pour les concerts.',
                'stock' => 0,
                'status' => ProductStatus::SoldOut,
                'categories' => ['category_2'],
                'orderItems' => ['order_item_15', 'order_item_16'],
                'image' => 'image_9'
            ],
            [
                'name' => 'Soft & Wet',
                'price' => 50000.00,
                'description' => 'L\'un des meilleurs piano sur le marché.',
                'stock' => 1,
                'status' => ProductStatus::Available,
                'categories' => ['category_5'],
                'orderItems' => ['order_item_17'],
                'image' => 'image_10'
            ],
        ];

        foreach ($productsData as $key => $productData)
        {
            $product = $this->createProduct($productData);
            $manager->persist($product);
            $this->addReference(self::PRODUCT_REF_PREFIX.($key + 1), $product);
        }

        $manager->flush();
    }

    private function createProduct(array $data): Product 
    {
        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $product->setDescription($data['description']);
        $product->setStock($data['stock']);
        $product->setStatus($data['status']);

        foreach ($data['categories'] as $key => $category) {
            $product->addCategory($this->getReference($category));
        }

        foreach ($data['orderItems'] as $key => $orderItem) {
            $product->addOrderItem($this->getReference($orderItem));
        }

        $product->setImage($this->getReference($data['image']));

        return $product;
    }

    public function getDependencies()
    {
        return [
            
        ];
    }
}
