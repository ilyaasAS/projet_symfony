<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Sauvegarde un objet Post en base de données
     * 
     * @param Post $post L'entité Post à sauvegarder
     */
    public function sauvegarder(Post $post): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($post);  // Persiste l'entité
        $entityManager->flush();         // Applique les changements en base de données
    }

    public function remove (Post $post) {

        $entityManager = $this->getEntityManager();
        $entityManager->remove($post);
        $entityManager->flush();

    }

    /**
     * Recherche d'un tableau d'objets Post avec un champ spécifique (exemple commenté)
     *
     * @param mixed $value La valeur à rechercher
     * 
     * @return Post[] Retourne un tableau d'objets Post
     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    /**
     * Recherche un objet Post par un champ spécifique (exemple commenté)
     *
     * @param mixed $value La valeur à rechercher
     * 
     * @return Post|null Retourne un seul objet Post ou null si non trouvé
     */
    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
