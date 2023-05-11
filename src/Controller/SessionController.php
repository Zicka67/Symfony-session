<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Student;
use App\Entity\Programme;
use App\Form\SessionType;
use App\Form\ProgrammeType;
use App\Form\ChangeFormerType;
use Doctrine\ORM\EntityManager;
use App\Repository\ModulesRepository;
use App\Repository\SessionRepository;
use App\Repository\StudentRepository;
use App\Repository\ProgrammeRepository;
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
    public function showDetailsSession($id, Request $request, EntityManagerInterface $entityManager, ModulesRepository $modulesRepository, SessionRepository $sessionRepository, StudentRepository $studentRepository, ProgrammeRepository $programmeRepository): Response
    {
        $session = $sessionRepository->find($id);
        $studentsNotInSession = $studentRepository->findStudentsNotInSession($id);
        $modules = $modulesRepository->getSessionModules($id);
        $programmes = $entityManager->getRepository(Programme::class)->findBy(['Session' => $id]);
        $programmesNotInSession = $programmeRepository->findProgrammesNotInSession($id);
        $changeFormerForm = $this->createForm(ChangeFormerType::class, $session);
        $allProgrammes = $entityManager->getRepository(Programme::class)->findAll();

        $programmeForms = [];
        foreach ($programmesNotInSession as $programme) {
            $form = $this->createForm(ProgrammeType::class, $programme);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
                $this->addFlash('success', 'La durée du programme a été mise à jour avec succès.');
                return $this->redirectToRoute('app_showDetailsSession', ['id' => $id]);
            }

            $programmeForms[$programme->getId()] = $form->createView();
        }

        $changeFormerForm->handleRequest($request);
        if ($changeFormerForm->isSubmitted() && $changeFormerForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le former a été changé avec succès.');
            return $this->redirectToRoute('app_showDetailsSession', ['id' => $id]);
        }

        return $this->render('formation/detailSession.html.twig', [
            'session' => $session,
            'studentsNotInSession' => $studentsNotInSession,
            'modules' => $modules,
            'programmes' => $programmes,
            'allProgrammes' => $allProgrammes,
            'programmesNotInSession' => $programmesNotInSession,
            'changeFormerForm' => $changeFormerForm->createView(),
            'programmeForms' => $programmeForms
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

        $this->addFlash('success', 'L\'étudant a été ajoutée avec succès.');

    
        return $this->redirectToRoute('app_showDetailsSession', ['id' => $id]);
    }

    #[Route('/sessions/{sessionId}/students/remove/{studentId}', name: 'app_removeStudentFromSession')]
    public function removeStudentFromSession($sessionId, $studentId, EntityManagerInterface $entityManager, SessionRepository $sessionRepository): Response
    {
        $session = $sessionRepository->find($sessionId);
        $student = $entityManager->getRepository(Student::class)->find($studentId);
        
        $session->removeStudent($student);
        $entityManager->flush();

        $this->addFlash('success', 'L\'étudiant a été supprimé avec succès.');


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

            $this->addFlash('success', 'La session a été ajoutée avec succès.');


            return $this->redirectToRoute('formation_sessions', ['id' => $session->getFormation()->getId()]);
        }

        return $this->render('session/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/session/{session_id}/add-programme/{programme_id}', name: 'add_programme_to_session')]
    public function addProgrammeToSession(int $session_id, int $programme_id, SessionRepository $sessionRepository, ProgrammeRepository $programmeRepository, EntityManagerInterface $entityManager): Response
    {
        $session = $sessionRepository->find($session_id);
        $programme = $programmeRepository->find($programme_id);
    
        if ($session && $programme) {
            // Associez le programme à la session
            $session->addProgramme($programme);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_showDetailsSession', ['id' => $session_id]);
    }

    #[Route('/session/{sessionId}/remove-program/{programId}', name: 'app_removeProgramFromSession')]
    public function removeProgramFromSession(int $sessionId, int $programId, SessionRepository $sessionRepository, ProgrammeRepository $programmeRepository, EntityManagerInterface $entityManager): Response
    {
        $session = $sessionRepository->find($sessionId);
        $programme = $programmeRepository->find($programId);

        if ($session && $programme) {
            
            $session->removeProgramme($programme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_showDetailsSession', ['id' => $sessionId]);
    }


    #[Route('/session/{id}/delete', name: 'app_sessionDelete')]
    public function deleteSession($id, EntityManagerInterface $entityManager, SessionRepository $sessionRepository): Response
    {
        $session = $sessionRepository->find($id);
        
        if (!$session) {
            throw $this->createNotFoundException('La session avec l\'id ' . $id . ' n\'a pas été trouvée.');
        }

        $this->addFlash('success', 'La session a été supprimée avec succès.');

    
        $entityManager->remove($session);
        $entityManager->flush();
    
        return $this->redirectToRoute('formation_sessions', ['id' => $session->getFormation()->getId()]);
    }

    // #[Route('/formation', name: 'programmes_list', methods: ['GET'])]
    // public function list(EntityManagerInterface $entityManager): Response
    // {
    //     $programmes = $entityManager->getRepository(Programme::class)->findAll();

    //     return $this->render('formation/detailSession.html.twig', [
    //         'programmes' => $programmes,
    //     ]);
    // }
    
    
}
