<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByPartialTitle($searchText)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.title LIKE :searchText')
            ->setParameter('searchText', '%'.$searchText.'%')
            ->getQuery()
            ->getResult();
    }

    public function findByPartialDescription($searchText)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.description LIKE :searchText')
            ->setParameter('searchText', '%'.$searchText.'%')
            ->getQuery()
            ->getResult();
    }


    public function numberOfProducts(){
        $entitymanager=$this->getEntityManager();
        $query= $entitymanager->createQuery("SELECT COUNT(p) FROM APP\Entity\Product p");
        return $query->getSingleScalarResult();
    
    }

//    /**
//     * @return Product[] Returns an array of Product objects
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

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
