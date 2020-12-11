<?php


namespace App\DataFixtures;

use Faker;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Action',
        'Aventure',
        'Animation',
        'Horreur',
        'Fantastique',
        'Comédie',
    ];

    public function load(ObjectManager $manager)
    {
        $i=1;
        foreach (self::CATEGORIES as $key => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $this->addReference('category_'. $i , $category);
            $i++;

        }

        $manager->flush();
    }


}
