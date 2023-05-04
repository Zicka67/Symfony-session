<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    #[Route('/formation/{id}', name: 'formation_sessions')]
    public function showSessions($id, EntityManagerInterface $entityManager): Response
    {
        $formation = $entityManager->getRepository(Formation::class)->find($id);

        if (!$formation) {
            throw $this->createNotFoundException('La formation' . $id . ' n\'a pas été trouvée.');
        }

        $session = $formation->getSessions();
        return $this->render('formation/sessions.html.twig', [
            'sessions' => $session,
            'formation' => $formation,
        ]);
    }  

    #[Route('/formationCreate/create', name: 'app_formationCreate')]
    public function createFormation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formation();

        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('app_formation', ['id' => $formation->getId()]);
        }

        return $this->render('formation/formationCreate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

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



}