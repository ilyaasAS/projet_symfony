<?php

namespace App\Repository;

use App\Entity\Uilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Uilisateur>
 */
class UilisateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Uilisateur::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Uilisateur) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    // 1. Méthode pour ajouter un Utilisateur dans la base de données
public function save(Uilisateur $nouvelUtilisateur, ?bool $flush = false) 
{
    // 1.1. Persiste l'entité Utilisateur dans le gestionnaire d'entités (Doctrine)
    $this->getEntityManager()->persist($nouvelUtilisateur);

    // 1.2. Tester si nous devons exécuter la transaction
    if ($flush) {
        // 1.2.2. Effectue les opérations de base de données (INSERT/UPDATE)
        $this->getEntityManager()->flush();
    }

    // 1.3. Retourner l'instance du nouvel utilisateur
    return $nouvelUtilisateur;
}


    //    /**
    //     * @return Uilisateur[] Returns an array of Uilisateur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Uilisateur
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
