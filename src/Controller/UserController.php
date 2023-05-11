<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePseudoType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user', name: 'app_userShow')]
    public function show(Security $security): Response
    {
        $user = $security->getUser();

        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/user/change', name: 'account_change_pseudo')]
    public function changePseudo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePseudoType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user');
            $this->addFlash('success', 'Votre pseudo a été modifié avec succès.');
        }

        return $this->render('user/change.html.twig', [
            'changePseudoForm' => $form->createView(),
        ]);
    }
}







