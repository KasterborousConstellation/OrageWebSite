<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LessonController extends AbstractController
{
    #[Route('/lesson', name: 'lessons')]
    public function index(): Response
    {   
        $this->denyAccessUnlessGranted("ROLE_USER");
        return $this->render("lesson/index.html.twig");
    }
}
