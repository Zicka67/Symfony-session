<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function save(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // public function findStudentsNotInSessionDetailSession(): array
    // {
    //     $entityManager = $this->getEntityManager();
    
    //     $query = $entityManager->createQuery(
    //         'SELECT s
    //         FROM App\Entity\Student s
    //         WHERE NOT EXISTS (
    //             SELECT 1
    //             FROM App\Entity\Session ss
    //             JOIN ss.students st
    //             WHERE st.id = s.id
    //         )'
    //     );
    
    //     return $query->getResult();
    // }

    public function findStudentsNotInSessionDetailSession($session_id)
 {
        //On récupère l'objet entityManager ( gestion entity )
        $em = $this->getEntityManager();
        //On créer un nouvel objet Query avec doctrine pour construire les sous requêtes
        $sub = $em->createQueryBuilder();

        // On stock l(objet dans une variable
        $qb = $sub;
        // sélectionner tous les stagiaires d'une session dont l'id est passé en paramètre
        $qb->select('s')
        ->from('App\Entity\Stagiaire', 's')
        ->leftJoin('s.sessions', 'se')
        ->where('se.id = :id');

        //On créer un nouvel objet Query avec doctrine pour construire la requete PRINCIPALE
        $sub = $em->createQueryBuilder();
        // sélectionner tous les stagiaires qui ne SONT PAS (NOT IN) dans le résultat précédent
        // on obtient donc les stagiaires non inscrits pour une session définie
        $sub->select('st')
        ->from('App\Entity\Stagiaire', 'st')
        ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
        // requête paramétrée
        ->setParameter('id', $session_id)
        // trier la liste des stagiaires sur le nom de famille
        ->orderBy('st.nom');

        // renvoyer le résultat
        $query = $sub->getQuery();
        return $query->getResult();
        //Ou directement return $sub->getQuery()
}


    public function findStudentsNotInSession($sessionId)   // a modifier
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT s
        FROM App\Entity\Student s
        WHERE NOT EXISTS (
            SELECT 1
            FROM App\Entity\Session ss
            JOIN ss.students st
            WHERE st.id = s.id
            AND ss.id = :sessionId
        )'
    )->setParameter('sessionId', $sessionId);

    return $query->getResult();
}
    


//    /**
//     * @return Student[] Returns an array of Student objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Student
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
