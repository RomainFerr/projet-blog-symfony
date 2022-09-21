<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BienvenueController extends AbstractController
{
    #[Route('/bienvenue', name: 'app_bienvenue')]
    public function bienvenue(): Response
    {
        return $this->render('bienvenue/index.html.twig');
    }

    #[Route('/bienvenue/{nom}', name: 'app_bienvenue-personne')]
    public function bienvenuePersonne($nom): Response
    {
        return $this->render('bienvenue/bienvenue-personne.html.twig', [
            "nom"=>$nom
        ]);
    }

    #[Route('/bienvenus', name: 'app_bienvenus')]
public function bienvenues(): Response
{
    //déclarer un tableau avec 3 prénom

    $noms=["Didier","Damien","Jean"];
    // la vue affiche bienvneu au 3 personnes

    return $this->render('bienvenue/bienvenus.html.twig', [
        "noms"=>$noms
    ]);
}
}
