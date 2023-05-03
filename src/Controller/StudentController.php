<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/students', name: 'app_listStudients')]
    public function studentList(EntityManagerInterface $entityManager): Response
    {
        $students = $entityManager->getRepository(Student::class)->findAll();

        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }

    #[Route('/students/{id}', name: 'app_showDetailsStudent')]
    public function showDetailsStudent($id, EntityManagerInterface $entityManager, StudentRepository $studentRepository): Response
    {
        $student = $entityManager->getRepository(Student::class)->find($id);
        $sessions = $student->getSession();
        // $studentsNotInSession = $studentRepository->findStudentsNotInSessionDetailSession();
    
        return $this->render('student/show.html.twig', [
            'student' => $student,
            'sessions' => $sessions,
            // 'studentsNotInSession' => $studentsNotInSession
        ]);
    }
    
    // #[Route('/not_in_session', name: 'app_show_students_not_in_session')]
    // public function showStudentsNotInSession(StudentRepository $studentRepository): Response
    // {
    //     $studentsNotInSession = $studentRepository->findStudentsNotInSession();

    //     return $this->render('student/not_in_session.html.twig', [
    //         'studentsNotInSession' => $studentsNotInSession,
    //     ]);
    // }

    
}
