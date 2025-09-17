<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PrivacyController extends AbstractController
{
    #[Route('/privacy', name: 'app_privacy')]
    public function privacy(): Response
    {
        return $this->render('privacy/index.html.twig', [
            'controller_name' => 'PrivacyController',
        ]);
    }
    #[Route('/privacy', name: 'app_terms')]
    public function terms(): Response
    {
        return $this->render('privacy/index.html.twig', [
            'controller_name' => 'PrivacyController',
        ]);
    }
    #[Route('/privacy', name: 'app_cookies')]
    public function cookies(): Response
    {
        return $this->render('privacy/index.html.twig', [
            'controller_name' => 'PrivacyController',
        ]);
    }

}
