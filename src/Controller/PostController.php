<?php

namespace App\Controller;

use App\Entity\ItemCollection;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\ItemCollectionRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Corriger l'erreur
    // #[Route('/post/{id}', name: 'app_afficher_post')]
    // public function afficher(Post $post, $id)
    // {

    //     return $this->render('post/afficher.html.twig', [
    //         'post' => $post,
    //     ]);
    // }

    #[Route('/collection/{id}/post/ajouter', name: 'app_ajouter_post', methods: ['POST', 'GET'])]
    public function ajouter(
        Request $request,
        $id,
        PostRepository $postRepository,
        SluggerInterface $slugger,
        ItemCollectionRepository $itemCollectionRepository,
        #[Autowire('%kernel.project_dir%/public/uploads/images')] string $brochuresDirectory
    ) {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $post = new Post();
        $collection = $this->entityManager->getRepository(ItemCollection::class)->find($id);

        if (!$collection) {
            throw $this->createNotFoundException('Collection non trouvée.');
        }

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move($brochuresDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement du fichier.');
                }

                $post->setImage($newFilename);
            }

            $collection->addPosts($post);
            $itemCollectionRepository->sauvegarder($collection);
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('post/ajouter.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/collection/{id}/post/modifier', name: 'app_modifier_post')]
    public function modifier(Request $request, $id, PostRepository $postRepository, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/images')] string $brochuresDirectory)
    {
        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException('Post non trouvé.');
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'image si elle a été modifiée
            $file = $form->get('image')->getData();

            if ($file) {
                // Si une nouvelle image est téléchargée, gérer l'upload
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    // Déplacer l'image vers le répertoire d'uploads
                    $file->move($brochuresDirectory, $newFilename);

                    // Supprimer l'ancienne image si elle existe
                    if ($post->getImage()) {
                        $oldImagePath = $brochuresDirectory . '/' . $post->getImage();
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath); // Supprimer l'ancienne image
                        }
                    }

                    // Mettre à jour le post avec le nouveau nom de fichier
                    $post->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement du fichier.');
                }
            }

            // Sauvegarder les changements
            $this->entityManager->flush();

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('post/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/post/{id}/supprimer', name: 'app_supprimer_post')]
    public function supprimer(PostRepository $postRepository, $id)
    {
        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException('Post non trouvé.');
        }

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        $this->addFlash('success', 'Le post a été supprimé avec succès.');

        return $this->redirectToRoute('app_accueil');
    }
}
