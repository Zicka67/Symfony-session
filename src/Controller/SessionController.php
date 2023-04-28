<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(): Response
    {
        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }

    // //{id} permet de récupérer l'identifiant du student dans l'URL à pas oublier
    // #[Route('/session/student/{id}', name: 'app_session_student')]
    // // La fonction prend en paramètre un objet SessionRepository et un objet Student.
    // //On utilise SessionRepository $sessionRepository pour accèder a ces propiétés dans le controller
    // public function findByStudent(SessionRepository $sessionRepository, Student $student): Response
    // {
    //     //Récupère toutes les sessions dans lesquelles le student est inscrit avec la méthode findByStudent de l'objet SessionRepository et Student en paramètre
    //     $sessions = $sessionRepository->findByStudent($student);
        
    //     return $this->render('session/index.html.twig', [
    //         'sessions' => $sessions,
    //     ]);
    // }
}
