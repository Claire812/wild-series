<?php


namespace App\DataFixtures;


use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        'John Doe',
        'Robert Redford',
        'Mister been',
        'Leonardo Dicaprio',
        'Will Smith',
        'Daniel Radcliffe'
    ];

    public function load(ObjectManager $manager)
    {
        $i=1;
        for ($i = 1;  $i < 100; $i++){
            $actor = new Actor();
            $faker = Faker\Factory::create('fr_FR');
            $actor->setName($faker->name);
            $actor->addProgram($this->getReference('program_' . rand(1,6)));
            $manager->persist($actor);
            $this->addReference('actor_' . $i , $actor);
            $i++;
            }
        $manager->flush();

    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];

    }
}
