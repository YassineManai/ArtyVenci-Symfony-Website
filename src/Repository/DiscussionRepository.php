<?php

namespace App\Repository;

use App\Entity\Discussion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Discussion>
 *
 * @method Discussion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Discussion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Discussion[]    findAll()
 * @method Discussion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscussionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discussion::class);
    }

     /**
     * Récupère toutes les discussions avec les informations sur le destinataire.
     * Utilise LEFT JOIN pour inclure les informations sur le destinataire.
     *
     * @return Discussion[] Retourne un tableau de discussions avec les informations sur le destinataire.
     */
    public function findAllWithReceiver()
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.receiver', 'u') // Supposons que la relation entre Discussion et User soit nommée "receiver"
            ->addSelect('u') // Sélectionnez également les données de l'utilisateur (destinataire)
            ->getQuery()
            ->getResult();
    }

    // Dans DiscussionRepository.php

public function findByReceiverUsername($username)
{
    return $this->createQueryBuilder('d')
        ->join('d.receiver', 'r')
        ->andWhere('r.username = :username')
        ->setParameter('username', $username)
        ->getQuery()
        ->getResult();
}

public function findDiscussionsByUser($userId)
{
    return $this->createQueryBuilder('d')
        ->andWhere('d.idsender = :userId OR d.receiver = :userId')
        ->setParameter('userId', $userId)
        ->getQuery()
        ->getResult();
}

public function findExistingDiscussion($currentUserId, $receiverId): ?Discussion
{
    return $this->createQueryBuilder('d')
        ->andWhere('(d.idsender = :currentUserId AND d.receiver = :receiverId)')
        ->orWhere('(d.receiver = :currentUserId AND d.idsender = :receiverId)')
        ->setParameter('currentUserId', $currentUserId)
        ->setParameter('receiverId', $receiverId)
        ->getQuery()
        ->getOneOrNullResult();
}


//    /**
//     * @return Discussion[] Returns an array of Discussion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Discussion
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findDiscussionById(int $id): ?Discussion
{
    return $this->find($id);
}
public function SEARCH(string $name): array {
    $manager = $this->getEntityManager();
    $query = $manager->createQuery('SELECT d FROM App\Entity\Discussion d JOIN App\Entity\User u WHERE u.username LIKE :name AND ((d.idsender = u.idUser) OR (d.receiver = u.idUser))')
                    ->setParameter('name', '%' . $name . '%');
    $result = $query->getResult();

    if (empty($result)) {
        $discussion = new Discussion();
        $discussion->setIdsender(0);
        $result[] = $discussion;
    }

    return $result;
}

    public function findByNonEmptySig(): array
        {
        return $this->createQueryBuilder('d')
            ->where('d.sig IS NOT NULL')
            ->getQuery()
            ->getResult();
        }   
/*public function SEARCH(string $name): array {
    $manager = $this->getEntityManager();
    $query = $manager->createQuery('SELECT d 
    FROM App\Entity\Discussion d 
    JOIN App\Entity\User u 
    WHERE ( (d.idsender = :currentUserId) 
    AND ( (u.username LIKE :name AND (d.idsender = u.idUser OR d.receiver = u.idUser) ) ) )
    ')
                    ->setParameter('name', '%' . $name . '%');
    $result = $query->getResult();

    if (empty($result)) {
        $discussion = new Discussion();
        $discussion->setIdsender(0);
        $result[] = $discussion;
    }

    return $result;
}*/

}
