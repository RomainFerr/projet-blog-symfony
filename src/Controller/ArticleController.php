<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;

    //demander à symfony d'injecter une instance de ArticleRepository a la creation du controle, cad instance de article controller
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository=$articleRepository;
    }


    #[Route('/articles', name: 'app_articles')]

    // a l'appel de la méthode, symfony va créer un objet de la classe article repository
    // et le passer en paramètre de la méthode
    // mécanisme : injection de dépendances

    public function getArticles(): Response
    {
        // Récupérer les informations dans la base de données
        // Le contrôleur fait appel au modèle (une classe du modèle) afin de récupérer la liste des articles
        // $repository = new ArticleRepository();

        $articles = $this->articleRepository->findBy([],['createdAt'=>"DESC"]);

        return $this->render('article/index.html.twig' , [
            "articles"=>$articles
    ]);
    }



   #[Route('/article/{slug}', name: 'app_article_slug')]
    public function getArticle($slug): Response
    {

        $article = $this->articleRepository->findOneBy(["slug"=>$slug]);
       // $categorie = $article->getCategorie()->getTitre();

        return $this->render('article/article.html.twig' , [
            "article"=>$article//, "categorie"=>$categorie
        ]);
    }
}
