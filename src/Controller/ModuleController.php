<?php

namespace App\Controller;

use App\Entity\Modules;
use App\Entity\Category;
use App\Repository\ModulesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
        // #[Route('/module', name: 'app_module')]
        // public function index(ModulesRepository $modulesRepository): Response
        // {
        //     $modules = $modulesRepository->findAll();
            
        //     return $this->render('module/index.html.twig', [
        //         'controller_name' => 'ModuleController',
        //         'modules' => $modules,
        //     ]);
        // }

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

    #[Route('/module', name: 'app_module', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager): Response
    {
        $modules = $entityManager->getRepository(Modules::class)->findAll();

        return $this->render('module/index.html.twig', [
            'modules' => $modules,
        ]);
    }

}







