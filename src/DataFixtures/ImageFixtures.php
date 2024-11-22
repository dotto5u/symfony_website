<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Image;

class ImageFixtures extends Fixture
{
    private const IMAGE_REF_PREFIX = 'image_';

    public function load(ObjectManager $manager): void
    {
        $imagesData = [
            [
                'url' => 'yamaha_u1.jpg'
            ],
            [
                'url' => 'kawai-k300.jpg'
            ],
            [
                'url' => 'steinway_sons_B.jpg'
            ],
            [
                'url' => 'casio-privia-px870B.jpg'
            ],
            [
                'url' => 'roland_hp_704.jpg'
            ],
            [
                'url' => 'yamaha-clp-775.jpg'
            ],
            [
                'url' => 'f278.jpg'
            ],
            [
                'url' => 'korg_grandstage_88.jpg'
            ],
            [
                'url' => 'bluthner_model_1.jpg'
            ],
            [
                'url' => 'soft_&_wet.jpg'
            ],
        ];        

        foreach ($imagesData as $key => $imageData) {
            $image = $this->createImage($imageData);
            $manager->persist($image);
            $this->addReference(self::IMAGE_REF_PREFIX.($key + 1), $image);
        }

        $manager->flush();
    }

    private function createImage(array $data): Image
    {
        $image = new Image();
        $image->setUrl($data['url']);

        return $image;
    }
}