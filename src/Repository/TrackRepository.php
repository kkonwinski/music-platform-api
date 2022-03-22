<?php

namespace App\Repository;

use App\Entity\Track;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Track|null find($id, $lockMode = null, $lockVersion = null)
 * @method Track|null findOneBy(array $criteria, array $orderBy = null)
 * @method Track[]    findAll()
 * @method Track[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Track::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Track $entity, bool $flush = true): void
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
    public function remove(Track $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Track[] Returns an array of Track objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder("t")
            ->andWhere("t.exampleField = :val")
            ->setParameter("val", $value)
            ->orderBy("t.id", "ASC")
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Track
    {
        return $this->createQueryBuilder("t")
            ->andWhere("t.exampleField = :val")
            ->setParameter("val", $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findTracks()
    {
        return $this->createQueryBuilder("t")
            ->select("t", "a", "b")
            ->leftJoin("t.album", "a")
            ->leftJoin("a.band", "b")
            ->orderBy("t.id", "ASC")
            ->getQuery()
            ->getArrayResult();
    }

    public function findTrackById($id)
    {
        return $this->createQueryBuilder("t")
            ->select("t", "a", "b")
            ->leftJoin("t.album", "a")
            ->leftJoin("a.band", "b")
            ->andWhere("t.id = :val")
            ->setParameter("val", $id)
            ->orderBy("t.id", "ASC")
            ->getQuery()
            ->getArrayResult();
    }
}
