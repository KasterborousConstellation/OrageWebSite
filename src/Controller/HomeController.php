<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $em,Request $request): Response
    {
        $repo = $em->getRepository('App\Entity\Annonce');
        $annonces = $repo->createQueryBuilder('u')->andWhere('u.visible = true','CURRENT_TIMESTAMP() < u.expirateAt')->orderBy('u.createdAt', 'DESC')->setMaxResults(10)->getQuery()->getResult();
        return $this->render("home/index.html.twig", [
            "annonces" => $annonces
        ]);
    }
    #[Route('/article/{id}', name: 'article', requirements: ['id'=> '\d+'])]
    public function article(int $id,EntityManagerInterface $em): Response{
        $repo = $em->getRepository('App\Entity\Annonce');
        $user = $this->getUser();
        if($user != null and in_array("ROLE_ADMIN",$user->getRoles())){
            $ann = $repo->find($id);
            if($ann != null and (!$ann->isVisible() or $ann->getExpirateAt() < new \DateTimeImmutable('+2 hours'))){
                $this->addFlash("info","Vous êtes administrateur, vous pouvez voir cette annonce même si elle n'est pas visible ou expirée.");
            }
        }else{
            $ann = $repo->createQueryBuilder('a')->andWhere('a.id = :id','a.visible = true','CURRENT_TIMESTAMP() < a.expirateAt')
                ->setParameter('id',$id)
                ->getQuery()->getOneOrNullResult();
        }
        if($ann == null){
            $this->addFlash("error","L'annonce que vous recherchez n'existe pas ou n'est pas disponible.");
            return $this->redirectToRoute('home');
        }
        return $this->render('home/article.html.twig' , [
            "ann" => $ann
        ]);
    }
}
