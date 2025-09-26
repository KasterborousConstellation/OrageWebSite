<?php
namespace App\Controller;
use App\Form\SearchFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[IsGranted("ROLE_ADMIN")]
final class AdminPanelController extends AbstractController
{
    #[Route('/admin/panel', name: 'app_admin_panel')]
    public function index(EntityManagerInterface $em,UserPasswordHasherInterface  $hasher): Response
    {
        return $this->render('admin_panel/index.html.twig');
    }
    #[Route('/admin/users', name: 'app_admin_users')]
    public function users(EntityManagerInterface $em,Request $request ): Response
    {

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);
        $data ="";
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->get("q")->getData();
        }
        $repo = $em->getRepository("App\Entity\User");
        $paginator = $repo->paginateUserData($request, $data);
        return $this->render('admin_panel/users.html.twig',
            [
                "users" => $paginator,
                "form" => $form->createView()
                ]);
    }
    #[Route('/admin/users/delete/{id}', name: 'app_admin_users_delete', requirements: ['id'=> '\d+'] )]
    public function deleteUser(EntityManagerInterface $em,Request $request , int $id) :RedirectResponse{
        if($this->getUser()->getId() == $id){
            $this->addFlash("error", "Impossible de supprimer son propre compte!");
            return $this->redirectToRoute('app_admin_users');
        }
        $repo = $em->getRepository("App\Entity\User");
        $user = $repo->find($id);
        if($user == null){
            $this->addFlash("error", "Impossible de supprimer. Le compte n'existe pas !");
            return $this->redirectToRoute('app_admin_users');
        }
        $username = $user->getUsername();
        $em->remove($user);
        $em->flush();
        $this->addFlash("success","L'utilisateur $username a bien été supprimé.");
        return $this->redirectToRoute('app_admin_users');
    }
    #[Route('/admin/announces', name: 'app_admin_announces')]
    public function announces(EntityManagerInterface $em) :Response{
        $form = $this->createForm("App\Form\AnnounceFormType");
        $form->handleRequest(Request::createFromGlobals());
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $announce = $form->getData();
            //$announce->setCreatedAt(new \DateTimeImmutable());
            $announce->setAuthor($this->getUser());
            $announce->setCreatedAt(new \DateTimeImmutable());
            $announce->setExpirateAt(new \DateTimeImmutable("+1 month"));
            $announce->setVisible(false);
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $this->addFlash('success', 'Annonce créée avec succès ! Elle est en attente de validation par un administrateur.');
            $em->persist($announce);
            $em->flush();
            return $this->redirectToRoute('app_admin_announces');
        }
        return $this->render('admin_panel/announce.html.twig',
        [
                "form" => $form->createView()
            ]);
    }
    #[Route('/admin/announces/show', name: 'app_admin_announces_show')]
    public function show(EntityManagerInterface $em) :Response{
        $annonces = $em->getRepository("App\Entity\Annonce")->findAll();
        return $this->render('admin_panel/showannonces.html.twig', ['annonces' => $annonces]);
    }
    #[Route('/admin/announces/edit/{id}', name: 'app_admin_announces_edit')]
    public function edit(Request $request,EntityManagerInterface $em, int $id) :Response{
        $annonce = $em->getRepository('App\Entity\Annonce')->find($id);
        if($annonce == null){
            $this->addFlash('error',"Impossible de modifier. L'annonce n'existe pas !");
            return $this->redirectToRoute('app_admin_announces_show');
        }
        $form = $this->createForm('App\Form\ModifiedAnnounceType',
           $annonce);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('app_admin_announces_show');
        }
        return $this->render('admin_panel/modify.html.twig', ['form' => $form , 'slug']);
    }
    #[Route('/admin/announces/setVisible/{id}', name: 'app_admin_announces_visibility', requirements: ['id' => '\d+'])]
    public function visibility(int $id,EntityManagerInterface $em) :RedirectResponse{
        $repo = $em->getRepository('App\Entity\Annonce');
        $ann = $repo->createQueryBuilder('u')->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
        if($ann == null) {
            $this->addFlash('error', 'Annonce introuvable !');
            return $this->redirectToRoute('app_admin_announces_show');
        }else{
            $ann->setVisible(!$ann->isVisible());
            $em->persist($ann);
            $em->flush();
            $this->addFlash('success', 'Visibilité modifiée avec succès !');
            return $this->redirectToRoute('app_admin_announces_show');
        }
    }
    #[Route('/admin/createLesson', name: 'app_admin_cours')]
    public function cours(Request $request,EntityManagerInterface $em) :Response{
        $form = $this->createForm('App\Form\CoursFormType');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $cours = $form->getData();
            $cours->setVisibility(false);
            $em->persist($cours);
            $em->flush();
            return $this->redirectToRoute('app_admin_panel');
        }
        return $this->render('admin_panel/cours.html.twig', ['cours_form' => $form->createView()]);
    }
    #[Route('/admin/createCategory', name: 'app_admin_category')]
    public function category(Request $request,EntityManagerInterface $em) :Response{
        $form = $this->createForm('App\Form\CategorieType');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            $em->persist($category);
            $em->flush();
            $this->addFlash('success','Catégorie crée.');
            return $this->redirectToRoute('app_admin_panel');
        }
        return $this->render('admin_panel/category.html.twig', ['form' => $form->createView()]);
    }
    #[Route('/admin/createNiveau', name: 'app_admin_niveau')]
    public function niveau(Request $request,EntityManagerInterface $em) :Response{
        $form = $this->createForm('App\Form\NiveauType');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $niveau = $form->getData();
            $niveau->setOrdre(null);
            $em->persist($niveau);
            $em->flush();
            $this->addFlash('success','Niveau crée.');
            return $this->redirectToRoute('app_admin_panel');
        }
        return $this->render('admin_panel/niveau.html.twig', ['form' => $form->createView()]);
    }
}
