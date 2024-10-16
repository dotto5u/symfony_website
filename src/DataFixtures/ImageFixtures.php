<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Image;
use App\Entity\Product;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    private const IMAGE_REF_PREFIX = 'image_';

    public function load(ObjectManager $manager): void
    {
        $imagesData = [
            [
                'url' => 'yamaha-u1.jpg',
                'product' => 'product_1'
            ],
            [
                'url' => 'kawai-k300.jpg',
                'product' => 'product_2'
            ],
            [
                'url' => 'steinway_sons_B.jpg',
                'product' => 'product_3'
            ],
            [
                'url' => 'casio-privia-px870B.jpg',
                'product' => 'product_4'
            ],
            [
                'url' => 'roland_hp_704.jpg',
                'product' => 'product_5'
            ],
            [
                'url' => 'yamaha-clp-775.jpg',
                'product' => 'product_6'
            ],
            [
                'url' => 'f278.jpg',
                'product' => 'product_7'
            ],
            [
                'url' => 'korg_grandstage_88.jpg',
                'product' => 'product_8'
            ],
            [
                'url' => 'bluthner_model_1.jpg',
                'product' => 'product_9'
            ],
            [
                'url' => 'soft_&_wet.jpg',
                'product' => 'product_10'
            ],
        ]; 

        foreach ($imagesData as $key => $imageData)
        {
            $image = $this->createImage($imageData);
            $manager->persist($image);
            $this->addReference(self::IMAGE_REF_PREFIX.($key + 1), $image);
        }

        $manager->flush();
    }

    private function createImage(array $data): Image
    {   
        $image = new Image();
        $image->setName($data['name']);
        $image->setProduct($data['product']);

        return $image;
    }

    public function getDependencies()
    {
        return [
            
        ];
    }
}