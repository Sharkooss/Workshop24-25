<?php

namespace App\Repository;

use App\Entity\Challenged;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Challenged>
 *
 * @method Challenged|null find($id, $lockMode = null, $lockVersion = null)
 * @method Challenged|null findOneBy(array $criteria, array $orderBy = null)
 * @method Challenged[]    findAll()
 * @method Challenged[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChallengedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Challenged::class);
    }

//    /**
//     * @return Challenged[] Returns an array of Challenged objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Challenged
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
