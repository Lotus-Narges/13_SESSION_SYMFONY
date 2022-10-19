<?php

namespace App\Repository;

use App\Entity\Sessions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sessions>
 *
 * @method Sessions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sessions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sessions[]    findAll()
 * @method Sessions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sessions::class);
    }

    public function add(Sessions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sessions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //We call this function in SessionsController.php
    //We create our own query to get the list of unregistered interns
    public function getNotregisteredInterns ($idSession)
    {
        //Communication with the DB
        $doctrine = $this->getEntityManager();

        //Building the query
        $query1 = $doctrine->createQueryBuilder();
        $query1 ->select('intern')
                ->from('App\Entity\Intern', 'intern')
                ->leftJoin('intern.sessions', 'sessions')
                ->where('sessions.id = :id');

        $query2 = $doctrine->createQueryBuilder();
        $query2 ->select('int')
                ->from('App\Entity\Intern', 'int')
                ->where($query2->expr()->NOTIN('int.id', $query1->getDQL()))
                ->setParameter('id', $idSession)
                //Name of our property in entity file in symfony
                ->orderBy('int.last_name');

        $result = $query2->getQuery();
        return $result->getResult();
    }

//    /**
//     * @return Sessions[] Returns an array of Sessions objects
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

//    public function findOneBySomeField($value): ?Sessions
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
