<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Student;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    
        return $this->redirectToRoute('app_showDetailsSession', ['id' => $id]);
    }

    #[Route('/sessions/{sessionId}/students/remove/{studentId}', name: 'app_removeStudentFromSession')]
    public function removeStudentFromSession($sessionId, $studentId, EntityManagerInterface $entityManager, SessionRepository $sessionRepository): Response
    {
        $session = $sessionRepository->find($sessionId);
        $student = $entityManager->getRepository(Student::class)->find($studentId);
        
        $session->removeStudent($student);
        $entityManager->flush();

        return $this->redirectToRoute('app_showDetailsSession', ['id' => $sessionId]);
    }

    #[Route('/session/create', name: 'app_sessionCreate')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = new Session();

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('formation_sessions', ['id' => $session->getFormation()->getId()]);
        }

        return $this->render('session/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/session/{id}/delete', name: 'app_sessionDelete')]
    public function deleteSession($id, EntityManagerInterface $entityManager, SessionRepository $sessionRepository): Response
    {
        $session = $sessionRepository->find($id);
        
        if (!$session) {
            throw $this->createNotFoundException('La session avec l\'id ' . $id . ' n\'a pas été trouvée.');
        }
    
        $entityManager->remove($session);
        $entityManager->flush();
    
        return $this->redirectToRoute('formation_sessions', ['id' => $session->getFormation()->getId()]);
    }
    
}
