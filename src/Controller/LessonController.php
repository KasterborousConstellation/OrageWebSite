<?php

namespace App\Controller;

use App\Entity\Cours;
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

//use App\Entity\UserFavoriteCourse;

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
            ->where('l.visibility = true')
            ->getQuery()
            ->getResult();
        $totalCours = count($cours);
        // Construire l'arborescence : Catégorie → Niveau → Cours
        $arborescence = [];
        foreach ($cours as $c) {
            $cat = $c->getCategorie()->getLibele();
            $niv = $c->getNiveau()->getNomNiveau();

            if (!isset($arborescence[$niv])) {
                $arborescence[$niv] = [];
            }
            if (!isset($arborescence[$niv][$cat])) {
                $arborescence[$niv][$cat] = [];
            }
            $arborescence[$niv][$cat][] = $c;
        }

        // Récupérer les cours favoris de l'utilisateur connecté
        /*$favoris = [];
        $user = $this->getUser();
        if ($user) {
            $favoris = $this->em->getRepository(UserFavoriteCourse::class)
                ->createQueryBuilder('uf')
                ->join('uf.cours', 'c')
                ->select('c')
                ->where('uf.user = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult();
        }*/


        return $this->render('lesson/index.html.twig', [
            'arborescence' => $arborescence,
            'totalCours' => $totalCours,
            'cours' => $cours,
            //'favoris' => $favoris,
        ]);
    }

    /*
        #[Route('/{id}/favorite', name: 'lesson_toggle_favorite', methods: ['POST'])]
        public function toggleFavorite(Request $request, Cours $cours): JsonResponse
        {
            $user = $this->getUser();
            if (!$user) {
                return $this->json(['error' => 'Non connecté'], 401);
            }

            $existing = $this->em->getRepository(UserFavoriteCourse::class)->findOneBy([
                'user' => $user,
                'cours' => $cours
            ]);

            if ($existing) {
                $this->em->remove($existing);
            } else {
                $fav = new UserFavoriteCourse();
                $fav->setUser($user);
                $fav->setCours($cours);
                $this->em->persist($fav);
            }

            $this->em->flush();

            return $this->json(['success' => true]);
        }*/
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
        if(!$lesson->isVisibility() and !$user->canModifyLesson($lesson)){
            $this->addFlash('error',"Vous n'avez pas la permission de voir ce cours.");
            return $this->redirectToRoute('home');
        }
        return $this->render('lesson/lessonShow.html.twig', ['lesson' => $lesson]);
    }
}
