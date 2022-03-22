<?php

namespace App\Repository;

use App\Entity\Band;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Band|null find($id, $lockMode = null, $lockVersion = null)
 * @method Band|null findOneBy(array $criteria, array $orderBy = null)
 * @method Band[]    findAll()
 * @method Band[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Band::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Band $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Band $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Band[] Returns an array of Band objects
    //  */

    public function findBands()
    {
        return $this->createQueryBuilder('b')
        ->select("b","a")
            ->leftJoin("b.albums", "a")
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function findBandsById($id)
    {
        return $this->createQueryBuilder('b')
            ->select("b","a")
            ->leftJoin("b.albums", "a")
            ->andWhere('b.id = :val')
            ->setParameter('val', $id)
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getArrayResult()
            ;
    }
    /*
    public function findOneBySomeField($value): ?Band
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
