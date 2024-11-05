<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

//    /**
//     * @return Post[] Returns an array of Post objects
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

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


//////////=====================================FAILED ATTEMPT=========================/////////
//    public function getPostsByForum($idforum){                                                
//       return $this->createQueryBuilder('p')
//                    ->where('p.idForum = :idF')
//                    ->setParameter('idF','%'.$idforum.'%')
//                    ->getQuery()
//                    ->getResult(); //[]
//    }
//////////===========================================================================/////////

    public function getPostsByForumQueryBuilder($idforum){
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
            ->select('p')
            ->from('App\Entity\Post', 'p')
            ->where('p.idForum = :idF')
            ->setParameter('idF', $idforum)
        ;
        return $queryBuilder->getQuery()->getResult();
    }
    public function getPostsByForumNormalSQL($idforum){
        $manager = $this->getEntityManager();
        $req = $manager->createQuery('SELECT p FROM App\Entity\Post p WHERE p.idForum = :idF')
        ->setParameter('idF',$idforum);
        return $req->getResult();
    
    }

    public function SortByLikes($idforum){
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
            ->select('p')
            ->from('App\Entity\Post', 'p')
            ->where('p.idForum = :idF')
            ->setParameter('idF', $idforum)
            ->orderBy('p.likeNumber', 'DESC');
        ;
        return $queryBuilder->getQuery()->getResult();
    }
    public function SortByLikesNormalSQL($idforum)
    {
        $manager = $this->getEntityManager();
        $query = $manager->createQuery('
            SELECT p 
            FROM App\Entity\Post p 
            WHERE p.idForum = :idF
            ORDER BY p.likeNumber DESC'
        )->setParameter('idF', $idforum);

        return $query->getResult();
    }

}
