<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact" )
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $lastName =  $form->get('lastName')->getData();
            $firstName = $form->get('firstName')->getData();
            $email = $form->get('email')->getData();
            $motif = $form->get('motif')->getData();
            $message = $form->get('message')->getData();



            $message = (new \Swift_Message($motif))
                ->setFrom($email)
                ->setTo('walidazzimani@gmail.com')
                ->setBody(
                    $this->renderView(
                        // templates/emails/registration.html.twig
                        'email/contact.html.twig',
                        ['lastName' => $lastName, 'firstName' => $firstName, 'email' => $email, 'motif' => $motif, 'message' => $message]
                    ),
                    'text/html'
                );


            $mailer->send($message);


            $this->addFlash('success', 'Email envoyé avec succes ! ');

            return $this->redirectToRoute('homepage');
        }




        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
