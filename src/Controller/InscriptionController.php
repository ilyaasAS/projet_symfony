<?php

namespace App\Controller;

use App\Entity\Uilisateur;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;

class InscriptionController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UilisateurRepository $userRepository; // Déclaration du repository

    // Injection de dépendances via le constructeur
    public function __construct(EntityManagerInterface $entityManager, UilisateurRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository; // Initialisation du repository
    }

    #[Route('/inscription', name: 'app_inscription')]
    public function index(Request $request): Response
    {
        // Créer un nouvel utilisateur
        $utilisateur = new Uilisateur();

        // Créer le formulaire
        $form = $this->createForm(InscriptionType::class, $utilisateur);

        // Traiter le formulaire
        $form->handleRequest($request);

        // Vérifiez si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Vérifier si l'utilisateur existe déjà
            $existingUser = $this->userRepository->findOneBy(['email' => $utilisateur->getEmail()]);
            if ($existingUser) {

                // Ajouter une erreur au formulaire si l'utilisateur existe déjà
                $form->addError(new FormError('Un utilisateur avec cet email existe déjà.'));
            } else {

                // Hashage du mot de passe
                $utilisateur->setPassword(password_hash($utilisateur->getPassword(), PASSWORD_BCRYPT));

                // Enregistrer l'utilisateur dans la base de données
                $this->entityManager->persist($utilisateur);
                $this->entityManager->flush();

                // Redirection après l'enregistrement réussi
                $this->addFlash('success', 'Inscription réussie ! Bienvenue.');
                return $this->redirectToRoute('app_inscription');
            }
        }

        // Rendre la vue avec le formulaire
        return $this->render('pages/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
