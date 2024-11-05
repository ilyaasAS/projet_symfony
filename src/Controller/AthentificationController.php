<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\LoginType;

class AthentificationController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // Obtenir les erreurs d'authentification (le cas échéant)
        $error = $authenticationUtils->getLastAuthenticationError();

        // Obtenir le dernier email utilisé dans le formulaire
        $lastEmail = $authenticationUtils->getLastUsername();

        // Créer le formulaire de connexion
        $form = $this->createForm(LoginType::class);

        // Rendre la vue avec les données nécessaires
        return $this->render('athentification/login.html.twig', [
            'last_email' => $lastEmail,
            'error' => $error,
            'loginForm' => $form->createView(),
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        // Controleur peut etre vide!
    }
}
