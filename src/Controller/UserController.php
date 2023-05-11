<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangeEmailType;
use App\Form\ChangePseudoType;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


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


    #[Route('/user/changePseudo', name: 'account_change_pseudo')]
    public function changePseudo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePseudoType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Votre pseudo a été modifié avec succès.');
            
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/changePseudo.html.twig', [
            'changePseudoForm' => $form->createView(),
        ]);
    }

    #[Route('/user/changeEmail', name: 'account_change_email')]
    public function changeEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangeEmailType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Votre adresse e-mail a été modifiée avec succès.');

            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/changeEmail.html.twig', [
            'changeEmailForm' => $form->createView(),
        ]);
    }

    //l'extension PHP Intelephense dans Visual Studio Code peut parfois donner des faux positifs sur les erreurs. Vous pouvez essayer de redémarrer votre IDE ou de réindexer votre projet.
    // #[Route('/user/changePassword', name: 'account_change_password')]
    // public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    // {
    //     $user = $this->getUser();
    //     $form = $this->createForm(ChangePasswordType::class);

    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $data = $form->getData();
    //         $currentPassword = $data['currentPassword'];
    //         $newPassword = $data['newPassword'];

    //         if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
    //             $this->addFlash('error', 'Mot de passe actuel incorrect.');
    //         } else {
    //             $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
    //             $user->setPassword($hashedPassword);

    //             $entityManager->flush();

    //             $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');

    //             return $this->redirectToRoute('app_user');
    //         }
    //     }

    //     return $this->render('user/change_password.html.twig', [
    //         'changePasswordForm' => $form->createView(),
    //     ]);
    // }

}









