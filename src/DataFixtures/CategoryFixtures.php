<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    private const CATEGORY_REF_PREFIX = 'category_';

    public function load(ObjectManager $manager): void
    {
        $categoriesData = [
            [
                'name' => 'Piano Droit'
            ],
            [
                'name' => 'Piano à Queue'
            ],
            [
                'name' => 'Piano Numériques'
            ],
            [
                'name' => 'Piano de Scène'
            ],
            [
                'name' => 'Piano Haut de Gamme'
            ],
        ];

        foreach ($categoriesData as $key => $categoryData) {
            $category = $this->createCategory($categoryData);
            $manager->persist($category);
            $this->addReference(self::CATEGORY_REF_PREFIX.($key + 1), $category);
        }

        $manager->flush();
    }

    private function createCategory(array $data): Category
    {
        $category = new Category();
        $category->setName($data['name']);

        return $category;
    }
}
