<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentaireFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

        for($i=0;$i<=200;$i++){

            $numArticle= $faker->numberBetween(0,99);
            $numUser= $faker->numberBetween(0,155);

            $commentaire=new Commentaire();
            $commentaire->setContenu($faker->paragraphs(3,true))
                        ->setCreatedAt($faker->dateTimeBetween('-6month'))
                        ->setArticle($this->getReference("article".$numArticle));
if ($numUser>=151) {
$commentaire->setUtilisateur(NULL);
}else{
    $commentaire->setUtilisateur($this->getReference("utilisateur" . $numUser));
}

            $manager->persist($commentaire);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ArticleFixtures::class , UtilisateurFixtures::class
        ];
    }

}
