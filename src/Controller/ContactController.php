<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $firstname = $form->get('firstname')->getData();
            $lastname = $form->get('name')->getData();
            $mailAddress = $form->get('mail')->getData();
            $message = $form->get('message')->getData();
            $file = $form->get('file')->getData();

            $mail = new Email();
            $mail->from($mailAddress)
                ->to("chloe.ardoise@gmail.com")
                ->subject("Nouveau contact de: $firstname $lastname")
                ->text($message);

            try {
                $mailer->send($mail);
                $this->addFlash("success", "Votre message a bien été envoyé !");
            }
            catch (TransportExceptionInterface $exception) {
                $this->addFlash("error", "Une erreur est sruvenue en envoyant votre message !");
            }
        }
        return $this->render('contact/index.html.twig', [
            'contact_form' => $form->createView(),
        ]);
    }
}
