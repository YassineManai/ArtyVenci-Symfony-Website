<?php

namespace App\Repository;

use App\Entity\AuctionParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AuctionParticipant>
 *
 * @method AuctionParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuctionParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuctionParticipant[]    findAll()
 * @method AuctionParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuctionParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuctionParticipant::class);
    }

    /////////////////   TESTING    /////////////////////
    public function getKenzon($idAuction,$idParti){
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
            ->select('a')
            ->from('App\Entity\AuctionParticipant', 'a')
            ->where('a.idParticipant = :idParti')
            ->andWhere('a.idAuction = :idAuction')
            ->setParameter('idParti', $idParti)
            ->setParameter('idAuction', $idAuction);
        ;
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function countParticipantsWithRating(int $auctionId): int
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder
            ->select('COUNT(ap.Id_AucPart) as count')
            ->from('App\Entity\AuctionParticipant', 'ap')
            ->where('ap.idAuction = :auctionId')
            ->andWhere('ap.love != 0')
            ->setParameter('auctionId', $auctionId);
            ;
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }


    public function averageRatingForAuction(int $auctionId): ?float
    {
        $average = $this->createQueryBuilder('ap')
            ->select('AVG(ap.rating) as average')
            ->where('ap.idAuction = :auctionId')
            ->andWhere('ap.rating != 0')
            ->setParameter('auctionId', $auctionId)
            ->getQuery()
            ->getSingleScalarResult();

        return $average !== null ? (float) $average : null;
    }
    ////////////////////////////////////////////////////

//    /**
//     * @return AuctionParticipant[] Returns an array of AuctionParticipant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AuctionParticipant
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
