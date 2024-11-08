<?php

namespace App\Controller;

use App\Entity\ItemCollection;
use App\Form\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CollectionController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    // Injection de l'EntityManagerInterface via le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Route pour afficher les détails d'une collection
#[Route('/collection/{id}', name: 'app_detail_collection', requirements: ['id' => '\d+'])]
public function detail(int $id): Response
{
    // Récupérer la collection depuis la base de données
    $collection = $this->entityManager->getRepository(ItemCollection::class)->find($id);

    // Vérifier si la collection existe
    if (!$collection) {
        throw $this->createNotFoundException('La collection demandée n\'existe pas.');
    }

    // Récupérer les posts associés à cette collection
    $posts = $collection->getPosts();

    // Rendre la vue avec les informations de la collection et les posts associés
    return $this->render('collection/detail.html.twig', [
        'collection' => $collection,
        'posts' => $posts,
    ]);
}


    // Ajout de la méthode pour afficher la liste des collections
    #[Route('/collection', name: 'app_liste_collections')]
    public function listeCollections(): Response
    {
        // Récupérer toutes les collections depuis la base de données
        $collections = $this->entityManager->getRepository(ItemCollection::class)->findAll();

        // Rendre la vue avec la liste des collections
        return $this->render('collection/liste.html.twig', [
            'collections' => $collections,
        ]);
    }

    // Route pour créer une collection
    #[Route('/collection/create', name: 'create_collection')]
    public function create(Request $request): Response
    {
        $collection = new ItemCollection();
        $form = $this->createForm(CollectionType::class, $collection);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer la collection en base de données
            $this->entityManager->persist($collection);
            $this->entityManager->flush();

            $this->addFlash('success', 'La collection a été créée avec succès.');

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('collection/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Route pour afficher le formulaire d'ajout de collection
    #[Route('/collection/ajouter', name: 'app_ajouter_collection')]
    public function ajouter(Request $request): Response
{
    // Vérifier si l'utilisateur est authentifié
    if (!$this->getUser()) {
        // Rediriger vers la page de connexion si non authentifié
        return $this->redirectToRoute('app_login');
    }

    // Créer une nouvelle instance de la collection
    $collection = new ItemCollection();

    // Ajouter la date avant de persister l'objet en base de données
    $collection->setDate(new \DateTimeImmutable()); // Définir la date actuelle

    // **Lier l'utilisateur à la collection**
    $collection->setUtilisateur($this->getUser());  // Associer l'utilisateur connecté à la collection

    // Créer le formulaire
    $form = $this->createForm(CollectionType::class, $collection);

    // Traiter la soumission du formulaire
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrer la collection dans la base de données
        $this->entityManager->persist($collection);
        $this->entityManager->flush();

        // Ajouter un message flash de succès
        $this->addFlash('success', 'La collection a été ajoutée avec succès.');

        // Rediriger vers une autre page après la soumission, par exemple, vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }

    // Rendre la vue avec le formulaire
    return $this->render('collection/ajouter.html.twig', [
        'form' => $form->createView(),
    ]);
}



    #[Route('/collection/{id}/modifier', name: 'app_modifier_collection', requirements: ['id' => '\d+'])]
    public function modifier(int $id, Request $request): Response
    {
        // Vérifier si l'utilisateur est authentifié
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer la collection depuis la base de données
        $collection = $this->entityManager->getRepository(ItemCollection::class)->find($id);

        // Vérifier si la collection existe
        if (!$collection) {
            throw $this->createNotFoundException('La collection demandée n\'existe pas.');
        }

        // Créer le formulaire et le pré-remplir avec les données de la collection
        $form = $this->createForm(CollectionType::class, $collection);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si un nouveau fichier de couverture est téléchargé, on le sauvegarde
            $coverFile = $form->get('cover')->getData();
            if ($coverFile) {
                // Gérer l'upload du fichier (par exemple, enregistrer dans un dossier)
                $newFilename = uniqid() . '.' . $coverFile->guessExtension();
                $coverFile->move(
                    $this->getParameter('cover_directory'), // Répertoire où stocker l'image
                    $newFilename
                );

                // Assigner le nom du fichier à la collection
                $collection->setCover($newFilename);
            }

            // Enregistrer les modifications dans la base de données
            $this->entityManager->flush();

            // Ajouter un message flash de succès
            $this->addFlash('success', 'La collection a été modifiée avec succès.');

            // Rediriger vers la page de la collection ou ailleurs
            return $this->redirectToRoute('app_accueil'); // Modifier si nécessaire
        }

        // Passer la collection à la vue pour l'afficher
        return $this->render('collection/modifier.html.twig', [
            'form' => $form->createView(),
            'collection' => $collection, // Passer la collection à la vue
        ]);
    }

    #[Route('/collection/{id}/supprimer', name: 'app_supprimer_collection', requirements: ['id' => '\d+'])]
    public function supprimer(int $id): Response
    {
        // Vérifier si l'utilisateur est authentifié
        if (!$this->getUser()) {
            // Rediriger vers la page de connexion si non authentifié
            return $this->redirectToRoute('app_login');
        }

        // Récupérer la collection depuis la base de données
        $collection = $this->entityManager->getRepository(ItemCollection::class)->find($id);

        // Vérifier si la collection existe
        if (!$collection) {
            throw $this->createNotFoundException('La collection demandée n\'existe pas.');
        }

        // Supprimer la collection de la base de données
        $this->entityManager->remove($collection);
        $this->entityManager->flush();

        // Ajouter un message flash de succès
        $this->addFlash('success', 'La collection a été supprimée avec succès.');

        // Rediriger vers la page de profil de l'utilisateur ou ailleurs (exemple vers la page d'accueil)
        return $this->redirectToRoute('app_accueil'); // Modifier si nécessaire, par exemple, vers la page du profil
    }
}
