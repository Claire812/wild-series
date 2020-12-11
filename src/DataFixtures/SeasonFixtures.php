<?php


namespace App\DataFixtures;

use Faker;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0;  $i < 100; $i++){
            $season = new Season();
            $faker = Faker\Factory::create('fr_FR');
            $season->setNumber($faker->numberBetween(0, 496461));
            $season->setYear($faker->year);
            $season->setDescription($faker->text(200));
            $season->setProgram($this->getReference('program_' . rand(1,6)));
            $manager->persist($season);
            $this->addReference('season_' . $i, $season);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
