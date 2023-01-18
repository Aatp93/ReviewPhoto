<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Photo;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function loadPhotos(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $photo = new Photo();
            $photo->setTitle('Photo numéro' . $i);
            $photo->setPostAt((new \DateTimeImmutable())->add(\DateInterval::createFromDateString('-' . $i . 'week')));
            $manager->persist($photo);
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager): void
    {
        $user = new User();
        $user = $user->setEmail('user1@dwwm.fr');
        $user = $user->setPseudo('user_1');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user1'));
        $manager->persist($user);
        $manager->flush();
    }


    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->loadPhotos($manager);
        $this->loadUsers($manager);

        $manager->flush();
    }
}
