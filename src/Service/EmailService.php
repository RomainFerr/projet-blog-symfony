<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private MailerInterface $mailer;

    /**
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $emmeteur
     * @param string $destinataire
     * @param string $objet
     * @param string $template
     * @param array $contexte
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function envoyerEmail(string $emmeteur,string $destinataire,string $objet, string $template, array $contexte) : void{
        //creer le mail
        $email = new TemplatedEmail();
        $email->from($emmeteur)
            ->to($destinataire)
            ->subject($objet)
            ->htmlTemplate($template)
            ->context(
                $contexte
            );

        //envoyer le mail
        $this->mailer->send($email);


 }

}