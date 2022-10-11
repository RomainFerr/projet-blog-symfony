<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
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
                ->setSlug($this->slugger->slug($article->getTitre())->lower())
                ->setPublie($faker->boolean);


        //associer l'article a une categorie
            //recuperer une reference d'une categorie
            $numCategorie= $faker->numberBetween(0,8);
            $article->setCategorie($this->getReference("categorie".$numCategorie));

            $this->addReference("article".$i,$article);
            //généré l'ordre INSERT

        $manager->persist($article); //INSERT INTO article values ("Titre 1","Contenu article 1")
        }
        //le flush permet d'envoyer l'ordre INSERT vers la base
        $manager->flush();

    }

    public function getDependencies()
    {
        return [
          CategorieFixtures::class
        ];
    }
}
