<?php

namespace App\Repository;

use App\Entity\Postlikes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Postlikes>
 *
 * @method Postlikes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Postlikes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Postlikes[]    findAll()
 * @method Postlikes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostlikesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postlikes::class);
    }
    public function getPostsLikesByPostQueryBuilder($idpost,$user){
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
            ->select('p')
            ->from('App\Entity\Postlikes', 'p')
            ->where('p.post = :idP')
            ->andWhere('p.user = :idU')
            ->setParameter('idP', $idpost)
            ->setParameter('idU', $user)
        ;
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

//    /**
//     * @return Postlikes[] Returns an array of Postlikes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Postlikes
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
