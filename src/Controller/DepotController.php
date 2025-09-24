<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DepotController extends AbstractController
{
    #[Route('/depot/{identifier}/{id}', name: 'app_depot_show_latest' , requirements: ['id' => '\d+'] , defaults: ['id' => -1])]
    public function index(EntityManagerInterface $em,string $identifier,int $id): Response
    {
        if($id ==-1){
            $depot = $em->getRepository('App\Entity\Depot')
                ->getLatestByIdentifier($identifier);
        }else{
            $depot = $em->getRepository('App\Entity\Depot')
                ->createQueryBuilder('u')
                ->where('u.version = :id and u.identifier = :identifier')
                ->setParameter('id',$id)
                ->setParameter('identifier',$identifier)
                ->getQuery()
                ->getOneOrNullResult();
        }
        if($depot == null){
            $this->addFlash('error','Ce fichier n\'existe pas');
            return $this->redirectToRoute('home');
        }
        return $this->redirect('/'.urlencode($depot->getPathPDF()));
    }
}
