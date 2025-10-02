<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Cours;
use App\Entity\Niveau;
use App\Utils\RedirectUtils;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/lesson')]
final class LessonController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'lessons', methods: ['GET'])]
    public function index(Request $request): Response
    {
        // Récupérer tous les cours avec leurs relations
        $cours = $this->em->getRepository(Cours::class)
            ->createQueryBuilder('l')
            ->andWhere('l.visibility = true')
            ->getQuery()
            ->getResult();
        // Construire l'arborescence : Catégorie → Niveau → Cours

        $niveau = $this->em->getRepository(Niveau::class)->findAll();
        $categorie = $this->em->getRepository(Categorie::class)->findAll();
        return $this->render('lesson/index.html.twig', [
            'cours' => $cours,
            'levels' => $niveau,
            'matiere' => $categorie
            //'favoris' => $favoris,
        ]);
    }
    #[Route('/exercices', name: 'exercices', methods: ['GET'])]
    public function exercices(): Response
    {
        return $this->render('lesson/exercices.html.twig', [
            'title' => 'Exercices - En construction',
        ]);
    }

    #[Route('/visibility/{id}', name: 'lessonVisibility', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function modifyVisibility(EntityManagerInterface $em,Request $request, int $id): RedirectResponse
    {
        $user = $this->getUser();
        /*VERIFY Permissions*/
        $lesson = $em->getRepository(Cours::class)->find($id);
        if($lesson ==null){
            $this->addFlash('error',"Ce cours n'existe pas.");
            return $this->redirectToRoute('home');
        }
        if(!$user->canModifyLesson($lesson)){
            $this->addFlash('error',"Vous n'avez pas la permission de modifier ce cours.");
            return $this->redirectToRoute('home');
        }
        $lesson->setVisibility(!$lesson->isVisibility());
        if($lesson->isVisibility()){
            $lesson->setCreatedAt(new \DateTimeImmutable());
        }
        $em->persist($lesson);
        $em->flush();
        //Return to sender
        return RedirectUtils::returnToSender($request);
    }

    #[Route('/{id}', name: 'lesson_show', methods: ['GET'])]
    public function lesson_show(EntityManagerInterface $em,Request $request, int $id): Response{
        $lesson = $em->getRepository(Cours::class)->find($id);
        $user = $this->getUser();
        if($lesson ==null){
            $this->addFlash('error',"Ce cours n'existe pas.");
            return $this->redirectToRoute('home');
        }
        if(!$lesson->isVisibility() and (!$user or !$user->canModifyLesson($lesson))){
            $this->addFlash('error',"Vous n'avez pas la permission de voir ce cours.");
            return $this->redirectToRoute('home');
        }
        return $this->render('lesson/lessonShow.html.twig', ['lesson' => $lesson]);
    }
}
