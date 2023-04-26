<?php

namespace App\Controller;

use App\Entity\Student;
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
}
