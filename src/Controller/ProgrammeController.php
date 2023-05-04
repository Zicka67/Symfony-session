<?php

namespace App\Controller;

use App\Entity\Programme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammeController extends AbstractController
{
    #[Route('/programme', name: 'app_programme')]
    public function index(): Response
    {
        return $this->render('programme/index.html.twig', [
            'controller_name' => 'ProgrammeController',
        ]);
    }

    #[Route('/programme', name: 'app_programme', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager): Response
    {
        $programmes = $entityManager->getRepository(Programme::class)->findAll();

        return $this->render('programme/index.html.twig', [
            'programmes' => $programmes,
        ]);
    }
}
