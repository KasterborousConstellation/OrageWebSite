<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminPanelController extends AbstractController
{
    #[Route('/admin/panel', name: 'app_admin_panel')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin_panel/index.html.twig');
    }
    #[Route('/admin/users', name: 'app_admin_users')]
    public function users(EntityManagerInterface $em,Request $request ): Response
    {
        dump($request->get("test","none"))  ;
        $repo = $em->getRepository("App\Entity\User");
        $paginator = $repo->paginateUserData($request);
        return $this->render('admin_panel/users.html.twig',["users" => $paginator]);
    }
}
