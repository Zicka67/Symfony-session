<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\SessionRepository;
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
    public function showDetailsStudent($id, EntityManagerInterface $entityManager): Response
    {
        $student = $entityManager->getRepository(Student::class)->find($id);
        $sessions = $student->getSession();
        $estInscrit = ($sessions->count() > 0);
    
        return $this->render('student/show.html.twig', [
            'student' => $student,
            'sessions' => $sessions,
            'estInscrit' => $estInscrit
        ]);
    }

    

    
}
