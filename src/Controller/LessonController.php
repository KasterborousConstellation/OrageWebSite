<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chapitre;
use App\Entity\Cours;
use App\Entity\Depot;
use App\Entity\Niveau;
use App\Form\ChapitreFormType;
use App\Form\FileInputType;
use App\Repository\DepotRepository;
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
        $modify = $request->get('modify', false);
        if($lesson ==null){
            $this->addFlash('error',"Ce cours n'existe pas.");
            return $this->redirectToRoute('home');
        }
        if((!$lesson->isVisibility() or $modify) and (!$user or !$user->canModifyLesson($lesson))){
            $this->addFlash('error',"Vous n'avez pas la permission de voir ce cours.");
            return $this->redirectToRoute('home');
        }
        if($user and $modify){
            $this->addFlash('info',"Vous êtes en mode modification.");
        }
        return $this->render('lesson/lessonShow.html.twig', ['lesson' => $lesson,'modify'=>$modify, 'user'=>$user]);
    }
    #[Route('/addChapter/{id}', name: 'lesson_chapter_add', requirements: ['id' => '\d+'], methods: ['GET','POST'])]
    public function editChapter(EntityManagerInterface $em,Request $request, int $id): Response
    {
        $user = $this->getUser();

        /*VERIFY Permissions*/
        $lesson = $em->getRepository(Cours::class)->find($id);
        if($lesson ==null){
            $this->addFlash('error',"Ce cours n'existe pas.");
            return $this->redirectToRoute('home');
        }
        if(!$user or !$user->canModifyLesson($lesson)) {
            $this->addFlash('error', "Vous n'avez pas la permission de modifier ce cours.");
            return $this->redirectToRoute('home');
        }
        $form = $this->createForm(ChapitreFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $chapter = $form->getData();
            $chapter->setCours($lesson);
            $max=0;
            $lesson->getCours()->map(function (Chapitre $chapter) use (&$max) {
                if($chapter->getOrdre() > $max) {
                    $max = $chapter->getOrdre();
                }
            });
            $max+=1;
            $chapter->setOrdre($max);
            $this->em->persist($chapter);
            $this->em->flush();
            $this->addFlash('success',"Le chapitre a été ajouté avec succès.");
            return $this->redirectToRoute('lesson_show',['id'=>$lesson->getId(),'modify'=>true]);
        }
        return $this->render('lesson/addChapter.html.twig', [
            'title' => 'Ajouter un chapitre - En construction',
            'form' => $form,
            'lesson' => $lesson
        ]);
    }
    #[Route('/addFile/{idl}-{idc}', name: 'lesson_chapter_depot_add', requirements: ['idl' => '\d+','idc' =>'\d+'], methods: ['GET','POST'])]
    public function addDepotToChapter(EntityManagerInterface $em,Request $request, int $idc,int $idl): Response{
        $cours = $em->getRepository(Cours::class)->createQueryBuilder('l')
            ->leftJoin('l.cours','c')
            ->where('l.id = :idl')
            ->andWhere('c.id = :idc')
            ->setParameter('idl',$idl)
            ->setParameter('idc',$idc)
            ->getQuery()
            ->getOneOrNullResult();
        $chapter = $em->getRepository(Chapitre::class)->find($idc);
        if($chapter ==null||$cours ==null){
            $this->addFlash('error',"Cet élement n'existe pas.");
            return RedirectUtils::returnToSender($request);
        }
        $user = $this->getUser();
        if(!$user or !$user->canModifyLesson($cours)) {
            $this->addFlash('error','Vous n\'avez pas la permission de modifier ce cours.');
            return $this->redirectToRoute('home');
        }
        $form= $this->createForm(FileInputType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $formResult = $form->getData();
            $file = $formResult['file'];
            $displayName = $formResult['name'];
            $fileType = $formResult['fileType'];
            $depot = $em->getRepository(Depot::class)->createDepotFromIdentifier(DepotRepository::generateRandomString(20),$file,$fileType);
            $depot->setDisplayName($displayName);
            $em->persist($depot);
            $chapter->addDepot($depot);
            $em->persist($chapter);
            $em->flush();
            return $this->redirectToRoute('lesson_show',['id'=>$cours->getId(),'modify'=>true]);
        }
        return $this->render('depot/addDepotToChapter.html.twig', ['form' => $form->createView(),'chapter'=>$chapter]);
    }
}
