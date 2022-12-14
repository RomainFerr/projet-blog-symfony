<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Service\EmailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function index(EmailService $emailService): Response
    {
        $emailService->envoyerEmail("emmetteur@test.fr","destinatire@test.fr","envoie mail","email/email.html.twig",["prenom"=>"Jeanne", "nom"=>"JENA"]);
        return  $this->redirectToRoute("app_articles");

    }

    #[Route('/contact', name: 'app_contact')]
    public function contacter(EmailService $emailService, ContactRepository $contactRepository, Request $request): Response
    {
        $contact = new Contact();

        $formContact=$this->createForm(ContactType::class, $contact);

        $formContact->handleRequest($request);

        if($formContact->isSubmitted() && $formContact->isValid()){
            $contact->setCreatedAt(new \DateTime());

            $contactRepository->add($contact ,true);

            $emailService->envoyerEmail($contact->getEmail(),"admin@blog.fr",$contact->getObjet(),"email/email.html.twig",[
                "contenu"=>$contact->getContenu(),
                "prenom"=>$contact->getPrenom(),
                "nom"=>$contact->getNom()
            ]);
            return $this->redirectToRoute("app_articles");
        }

        return $this->renderForm("email/contact.html.twig",[
            'formContact'=>$formContact
        ]);

    }


}
