<?php

namespace App\Repository;

use App\Entity\AuditInscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AuditInscription>
 *
 * @method AuditInscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuditInscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuditInscription[]    findAll()
 * @method AuditInscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuditInscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuditInscription::class);
    }

    //    /**
    //     * @return AuditInscription[] Returns an array of AuditInscription objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')1
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AuditInscription
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
