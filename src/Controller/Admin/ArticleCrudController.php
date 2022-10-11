<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Faker\Provider\Text;
use Symfony\Component\String\Slugger\SluggerInterface;
use function PHPUnit\Framework\returnArgument;

class ArticleCrudController extends AbstractCrudController
{
    private SluggerInterface $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger=$slugger;
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre', 'Titre'),
            TextEditorField::new('contenu')->setSortable(false)->hideOnIndex(),
            DateTimeField::new('createdAt')->hideOnForm()->setLabel('Créé le :'),
            TextField::new('slug')->hideOnForm(),
            AssociationField::new('categorie')->setRequired(false),
            BooleanField::new('publie')
        ];

    }

    //redéfinir la méthode persiste entity qui va être appeler lors de la création de l'article en base de donnée
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
//vérifier que la variable entityInstance est bien une instance de la classe article
        if(!$entityInstance instanceof Article) return;
        $entityInstance->setCreatedAt(new \DateTime());
        $entityInstance->setSlug($this->slugger->slug($entityInstance->getTitre())->lower());
//faire appel a la methode herite afin de persister l'entity
        parent::persistEntity($entityManager,$entityInstance);
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->setPageTitle(Crud::PAGE_INDEX,'Liste des Articles')
        ->setPageTitle(Crud::PAGE_NEW,'Création d\'un Article')
        ->setPageTitle(Crud::PAGE_EDIT,'Edition d\'un Article');
        $crud->setPaginatorPageSize(10)
            ->setPaginatorRangeSize(2);
        $crud->setDefaultSort(['createdAt'=>'DESC']);
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX, Action::NEW,
        function (Action $action){
          return  $action->setLabel('Ajouter Article')->setIcon('fa fa-plus');
        }
        )
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN,
            function (Action $action) {
        return $action->setLabel('Valider')->setIcon('fa fa-check');
        }
        )
        ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER
          )
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->update(Crud::PAGE_INDEX, Action::DETAIL,  function (Action $action) {
        return $action->setLabel('Détails');
    });
        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters->add("titre")->add("createdAt");
        return $filters;
    }


}
