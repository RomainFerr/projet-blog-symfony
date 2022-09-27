<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleFixtures extends Fixture
{
    private SluggerInterface $slugger;

    //Demander a symfony d'injecter le slugger au niveau du constructeur
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger=$slugger;
    }

    public function load(ObjectManager $manager): void
    {
        //initialiser faker
        $faker = Factory::create("fr_FR");

        for ($i=0;$i<100;$i++){
        $article = new Article();
        $article ->setTitre($faker->words($faker->numberBetween(1,10),true))
                 ->setContenu($faker->paragraphs(3,true))
            ->setCreatedAt($faker->dateTimeBetween('-6month'))
            ->setSlug($this->slugger->slug($article->getTitre())->lower());


            //généré l'ordre INSERT

        $manager->persist($article); //INSERT INTO article values ("Titre 1","Contenu article 1")
        }
        //le flush permet d'envoyer l'ordre INSERT vers la base
        $manager->flush();

    }
}
