<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    #[Route('/formation', name: 'app_formation')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Permet de vérifier si l'utilisateur est authentifié
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //créer une instance de FormationRepository en utilisant l'entityManager $entityManager
        $formationRepo = $entityManager->getRepository(Formation::class);

        //Le premier argument est un tableau associatif qui contient des critères de recherche optionnels
        //Le 2ieme argument est un tableau associatif qui indique ce qu'on veut chercher
        $formationList = $formationRepo->findBy([], ['title_formation' => 'ASC']);
       
        return $this->render('formation/index.html.twig', [
            'formations' => $formationList,
            
         
        ]);
    }

    #[Route('/formation/{id}/sessions', name: 'formation_sessions')]
    public function showSessions($id, EntityManagerInterface $entityManager): Response
    {
        $formation = $entityManager->getRepository(Formation::class)->find($id);
        $session = $formation->getSessions();
        return $this->render('formation/sessions.html.twig', [
            'sessions' => $session,
            'formation' => $formation,
        ]);
    }

   #[Route('/formation/{id}/sessions/{sessionId}', name: 'formation_detailSession')]
public function showDetailsSessions($id, $sessionId, EntityManagerInterface $entityManager): Response
{
    $formation = $entityManager->getRepository(Formation::class)->find($id);
    $session = $entityManager->getRepository(Session::class)->find($sessionId);

    return $this->render('formation/detailSession.html.twig', [
        'session' => $session,
        'formation' => $formation,
    ]);
}



}