<?php


namespace App\DataFixtures;
use App\Service\Slugify;
use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $episode = new Episode();
            $episode->setTitle($faker->streetName);
            $slugTitle = $this->slugify->generate($episode->getTitle());
            $episode->setSlug($slugTitle);
            $episode->setNumber($faker->numberBetween(1,10));
            $episode->setSynopsis($faker->text);
            $episode->setSeason($this->getReference('season_' .rand(1,6) ));
            $manager->persist($episode);
        }

        $manager->flush();

    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
