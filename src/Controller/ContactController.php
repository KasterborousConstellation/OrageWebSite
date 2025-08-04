<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\FaqQuestion;
use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig');
    }

    #[Route('/contact/mail',name: 'contact.mail')]
    public function mail(Request $request, MailerInterface $mailer) : Response
    {
        /**
         * This route is used to render the contact mail page.
         * 
         */
        $data = new ContactDTO();
        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            //Envoyer le mail
            $email = (new Email())
                ->from($data->email)
                ->to('contact@asso-orage.fr')
                ->subject('Contact depuis le site web')
                ->text($data->message)
                ->html('<p>Nom: ' . $data->name . '</p><p>Email: ' . $data->email . '</p><p>Message: ' . nl2br($data->message) . '</p>');
            $mailer->send($email);
            
            $this->addFlash('success', 'Votre ticket a été envoyé avec succès !');
            return $this->redirectToRoute('contact');
        }
        return $this->render('contact/mail.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/contact/faq',name : "contact.faq")]
    public function faq(EntityManagerInterface $entityManager) : Response
    {
        $product = $entityManager->getRepository(FaqQuestion::class);
        $faqQuestions = $product->findAll();
        $faqArray = array_map(function (FaqQuestion $faqQuestion) {
            return [
                'question' => $faqQuestion->getQuestionName(),
                'description' => $faqQuestion->getDescription(),
            ];
        }, $faqQuestions);
        return $this->render('contact/faq.html.twig', [
            'faqQuestions' => $faqArray,
        ]);
    }
}