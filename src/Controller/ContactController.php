<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig');
    }

    #[Route('/contact/mail',name: 'contact.mail')]
    public function mail() : Response
    {
        return $this->render('contact/mail.html.twig');
    }
    #[Route('/contact/faq',name : "contact.faq")]
    public function faq() : Response
    {
        return $this->render('contact/faq.html.twig');
    }
}