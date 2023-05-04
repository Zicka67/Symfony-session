<?php

namespace App\Controller;

use App\Entity\Modules;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(): Response
    {
        return $this->render('module/index.html.twig', [
            'controller_name' => 'ModuleController',
        ]);
    }

    #[Route('/modules/new', name: 'modules_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        //Creation d'un module 
        $module = new Modules();
        //Creation d'un form avec les données de moduletype
        $form = $this->createForm(ModulesType::class, $module);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //prepare
            $entityManager->persist($module);
            //execute
            $entityManager->flush();

            return $this->redirectToRoute('modules_show', ['id' => $module->getId()]);
        }

        return $this->render('modules/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modules/{id}', name: 'modules_show', methods: ['GET'])]
    public function show(Modules $module): Response
    {
        return $this->render('modules/show.html.twig', [
            'module' => $module,
        ]);
    }
}







