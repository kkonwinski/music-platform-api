<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Band;
use App\Entity\Track;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BandFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 3; $i++) {
            $band = new Band();
            $band->setName($faker->name);
            $manager->persist($band);
            for ($z = 0; $z < 3; $z++) {
                $album = new Album();
                $album->setBand($band);
                $album->setCover($faker->imageUrl());
                $album->setTitle($faker->userName);
                $album->setYear($faker->year());
                $album->setIsPromoted($faker->boolean());
                $manager->persist($album);
                for ($y = 0; $y < 3; $y++) {
                    $track = new Track();
                    $track->setAlbum($album);
                    $track->setTitle($faker->userName);
                    $track->setUrl($faker->url);
                    $manager->persist($track);
                }
            }
        }


        $manager->flush();
    }
}
