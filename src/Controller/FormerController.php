<?php

namespace App\Controller;

use App\Entity\Former;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormerController extends AbstractController
{
    // #[Route('/former', name: 'app_former')]
    // public function index(): Response
    // {
    //     return $this->render('former/index.html.twig', [
    //         'controller_name' => 'FormerController',
    //     ]);
    // }

    #[Route('/former', name: 'app_former')]
    public function formateursList(EntityManagerInterface $entityManager): Response
    {
        $former = $entityManager->getRepository(Former::class)->findAll();

        return $this->render('former/index.html.twig', [
            'formers' => $former,
        ]);
    }

}
