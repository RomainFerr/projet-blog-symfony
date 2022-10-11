<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Repository\CommentaireRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class ArticleController extends AbstractController
{

    private ArticleRepository $articleRepository;
    private CommentaireRepository $commentaireRepository;
    private UtilisateurRepository $utilisateurRepository;

    //demander à symfony d'injecter une instance de ArticleRepository a la creation du controle, cad instance de article controller
    public function __construct(ArticleRepository $articleRepository, CommentaireRepository $commentaireRepository,UtilisateurRepository $utilisateurRepository)
    {
        $this->articleRepository=$articleRepository;
        $this->commentaireRepository=$commentaireRepository;
        $this->utilisateurRepository=$utilisateurRepository;
    }


    #[Route('/articles', name: 'app_articles')]

    // a l'appel de la méthode, symfony va créer un objet de la classe article repository
        // et le passer en paramètre de la méthode
        // mécanisme : injection de dépendances

    public function getArticles(PaginatorInterface $paginator , Request $request): Response
    {
        // Récupérer les informations dans la base de données
        // Le contrôleur fait appel au modèle (une classe du modèle) afin de récupérer la liste des articles
        // $repository = new ArticleRepository();

        //mise en place de la pagination
        $articles = $paginator->paginate(
            $this->articleRepository->findBy(['publie'=>'true'],['createdAt'=>"DESC"]), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        // $articles = $this->articleRepository->findBy([],['createdAt'=>"DESC"]);

        return $this->render('article/index.html.twig' , [
            "articles"=>$articles
        ]);
    }














    #[Route('/articles/{slug}', name: 'app_article_slug',methods: ["GET","POST"])]
    public function getArticle($slug,Request $request ): Response
    {

        $article = $this->articleRepository->findOneBy(["slug"=>$slug]);
        // $categorie = $article->getCategorie()->getTitre();

        $commentaire = new Commentaire();
        $formCommentaire = $this->createForm(CommentaireType::class,$commentaire);

        $formCommentaire->handleRequest($request);

        if($formCommentaire->isSubmitted() && $formCommentaire->isValid()){
            $commentaire
                ->setCreatedAt(new \DateTime())
                ->setArticle($article)
            ;

            $this->commentaireRepository->add($commentaire,true);
            return $this->redirectToRoute("app_articles");
        }
        return $this->renderForm('article/article.html.twig' , [
            "article"=>$article,'formCommentaire'=>$formCommentaire
        ]);
    }









    #[Route('/articles/nouveau', name: 'app_articles_nouveau',methods: ["GET","POST"],priority: 1 )]
    public function insert(SluggerInterface $slugger, Request $request) : Response{
        $article = new Article();
        //création du formulaire
        $formArticle = $this->createForm(ArticleType::class,$article);

        //ajout d'une fonction pour savoir si le formulaire a été soumit ou pas
        $formArticle->handleRequest($request);
        //est ce que le formulaire a été soumit
        if($formArticle->isSubmitted() && $formArticle->isValid()){
            $article->setSlug($slugger->slug($article->getTitre())->lower())
                ->setCreatedAt(new \DateTime());
            $this->articleRepository->add($article,true);
            return $this->redirectToRoute("app_articles");
        }

        //appel d'une vue twig pour affiche ce formulaire
        return $this->renderForm("article/nouveau.html.twig", [
            'formArticle'=>$formArticle
        ]);

        /*        $article -> setTitre('Nouvelle Article 2')
                    -> setContenu("Contenu de l'article 2")
                    -> setSlug($slugger->slug($article->getTitre())->lower())
                    ->setCreatedAt(new \DateTime());
               $this->articleRepository->add($article,true);//grace à symfony 6
                return $this->redirectToRoute('app_articles') ;*/

    }








    #[Route('/articles/modifier/{slug}', name: 'app_articles_modifier',methods: ["GET","POST"],priority: 1 )]
    public function edit($slug, SluggerInterface $slugger, Request $request) : Response{
        $article = $this->articleRepository->findOneBy(["slug"=>$slug]);
        //création du formulaire
        $formArticle = $this->createForm(ArticleType::class,$article);

        //ajout d'une fonction pour savoir si le formulaire a été soumit ou pas
        $formArticle->handleRequest($request);
        //est ce que le formulaire a été soumit
        if($formArticle->isSubmitted() && $formArticle->isValid()){
            $article->setSlug($slugger->slug($article->getTitre())->lower());
            $this->articleRepository->add($article,true);
            return $this->redirectToRoute("app_articles");
        }

        //appel d'une vue twig pour affiche ce formulaire
        return $this->renderForm("article/modifier.html.twig", [
            'formArticle'=>$formArticle,
            'article'=>$article
        ]);
    }

}

