<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserChangePasswordType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/profile', name: 'app_user_profile'), IsGranted("ROLE_USER")]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
        ]);
    }

    #[Route('/user/change-password', name: 'app_user_change_password'), IsGranted("ROLE_USER")]
    public function changePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserChangePasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $currentPassword = $form->get('password')->getData();
            $currentPasswordValid = $userPasswordHasher->isPasswordValid($user, $currentPassword);
            if (!$currentPasswordValid) {
                $form->get('password')->addError(new FormError('Podane hasło jest nieprawidłowe'));
            } else {
                $newPassword = $form->get('newPassword')->getData();
                $hashedNewPassword = $userPasswordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedNewPassword);
                $userRepository->save($user, true);
                $this->addFlash('success', 'Hasło zostało zmienione');
            }
        }
        return $this->render('user/change-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
