<?php

namespace App\Repository;

use App\Entity\Readlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Readlist>
 *
 * @method Readlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Readlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Readlist[]    findAll()
 * @method Readlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReadlistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Readlist::class);
    }

    public function save(Readlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Readlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * This method allow us to find public readlist  based on number of readlist
     *
     * @param integer $nbRealdists
     * @return array
     */
    public function findPublicReadlist(?int $nbRealdists): array
    {
        $queryBUilder = $this->createQueryBuilder('r')
            ->where('r.isPublic = 1')
            ->orderBy('r.createdAt', 'DESC');


        if ($nbRealdists !== 0 || $nbRealdists !== null) {

            $queryBUilder->setMaxResults($nbRealdists);
        }
        return $queryBUilder->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Readlist[] Returns an array of Readlist objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Readlist
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
