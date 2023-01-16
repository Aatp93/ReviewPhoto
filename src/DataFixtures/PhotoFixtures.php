<?php

namespace App\DataFixtures;
use App\Entity\Photo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PhotoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
            for ($i = 1; $i <=5; $i++){
                $photo = new Photo();
                $photo -> setTitle('Photo numéro' .$i);
                $photo -> setPostAt((new \DateTimeImmutable()) -> add(\DateTimeImmutable:: createFromDateString('-'.$i.'week')));
                $manager -> persist($photo); 

            }
            

        $manager->flush();
    }
}
