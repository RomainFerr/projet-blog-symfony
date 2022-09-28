<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UtilisateurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        for($i=0;$i<=150;$i++){
            $utilisateur=new Utilisateur();
            $utilisateur->setNom($faker->lastName)
                    ->setPrenom($faker->firstName)
                    ->setPseudo($faker->userName);
            $this->addReference("utilisateur".$i,$utilisateur);

            $manager->persist($utilisateur);
        }

        $manager->flush();
    }
}
