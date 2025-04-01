<?php

namespace App\Repository;

use App\Entity\AuditFacture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AuditFacture>
 *
 * @method AuditFacture|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuditFacture|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuditFacture[]    findAll()
 * @method AuditFacture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuditFactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuditFacture::class);
    }

    //    /**
    //     * @return AuditFacture[] Returns an array of AuditFacture objects
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

    //    public function findOneBySomeField($value): ?AuditFacture
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findCountActionsByType(): array
    {
        return $this->createQueryBuilder('ai')
            ->select('ai.typeAction as type, COUNT(ai.id) as count')
            ->groupBy('ai.typeAction')
            ->orderBy('count', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
