<?php

namespace App\Repository;

use App\Entity\Forum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Forum>
 *
 * @method Forum|null find($id, $lockMode = null, $lockVersion = null)
 * @method Forum|null findOneBy(array $criteria, array $orderBy = null)
 * @method Forum[]    findAll()
 * @method Forum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forum::class);
    }

//    /**
//     * @return Forum[] Returns an array of Forum objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Forum
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function numberOfForums(){
        $entitymanager=$this->getEntityManager();
        $query= $entitymanager->createQuery("SELECT COUNT(f) FROM APP\Entity\Forum f");
        return $query->getSingleScalarResult();

    }
    public function searchByName(string $title): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.title LIKE :title')
            ->setParameter('title', '%' . $title . '%')
            ->getQuery()
            ->getResult();
    }
    public function SEARCH(string $title): array{
        $manager = $this->getEntityManager();
        $req = $manager->createQuery('SELECT f FROM App\Entity\Forum f WHERE f.title LIKE :idF')
        ->setParameter('idF','%' . $title . '%');
        $result = $req->getResult();
    
        if (empty($result)) {
            //$manager = $this->getEntityManager();
            //$req = $manager->createQuery('SELECT f FROM App\Entity\Forum f');
            //$result = $req->getResult();
            $forum = new Forum();
            $forum->setTitle("No records");
            $result[] = $forum;
        }

        return $result;
    }
}
