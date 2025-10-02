<?php

namespace App\Controller;

use App\Entity\Cours;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('profile/index.html.twig');
    }
    #[Route('/profile/delete', name: 'app_profile_delete')]
    public function delete(Request $request,EntityManagerInterface $em): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        if($user ==null){
            $this->addFlash('error',"Une erreur est survenue, veuillez réessayer plus tard.");
            return $this->redirectToRoute('home');
        }
        $request->getSession()->invalidate();
        $this->container->get('security.token_storage')->setToken(null);
        $cours = $em->getRepository(Cours::class)
            ->createQueryBuilder('l')
            ->where('l.author = :user')
            ->setParameter('user',$user)
            ->getQuery()
            ->getResult();
        foreach ($cours as $l){
            $l->setAuthor(null);
            $em->persist($l);
        }
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('home');
    }
}
