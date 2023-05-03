<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Student;
use App\Repository\SessionRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/sessions/{id}', name: 'app_showDetailsSession')]
    public function showDetailsSession($id, EntityManagerInterface $entityManager, StudentRepository $studentRepository): Response
    {
        $session = $entityManager->getRepository(Session::class)->find($id);
        $studentsNotInSession = $studentRepository->findStudentsNotInSession($id);
        // dd($studentsNotInSession);
        return $this->render('formation/detailSession.html.twig', [
            'session' => $session,
            'studentsNotInSession' => $studentsNotInSession
        ]);
    }

    #[Route('/sessions/{id}/add-student/{studentId}', name: 'app_addStudentToSession')]
    public function addStudentToSession($id, $studentId, EntityManagerInterface $entityManager): Response
    {
        $session = $entityManager->getRepository(Session::class)->find($id);
        $student = $entityManager->getRepository(Student::class)->find($studentId);
    
        if (!$session || !$student) {
            throw $this->createNotFoundException('La session ou l\'étudiant est introuvable.');
        }
    
        $session->addStudent($student);
    
        $entityManager->persist($session);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_detailSession', ['id' => $id]);
    }
}
