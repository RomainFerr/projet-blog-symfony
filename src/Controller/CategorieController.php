<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    private CategorieRepository $categorieRepository;
    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository=$categorieRepository;
    }


    #[Route('/categories', name: 'app_categories')]
    public function getCategories(): Response
    {
        $categories=$this->categorieRepository->findBy([],['titre'=>"ASC"]);
        return $this->render('categorie/index.html.twig',[
            "categories"=>$categories
            ]
        );
    }

    #[Route('/categorie/{slug}', name: 'app_categorie_slug')]
    public function getCategorie(): Response
    {
       // $categorie=$this->categorieRepository->findOneBy([],['slug'=>$slug]);
     //$articles=$categorie->getArticles();
        return $this->render('categorie/categorie.html.twig',[
                //"categorie"=>$categorie,"articles"=>$articles
            ]
        );
    }
}
