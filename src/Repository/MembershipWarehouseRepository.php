<?php

namespace App\Repository;

use App\Entity\MembershipWarehouse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MembershipWarehouse>
 *
 * @method MembershipWarehouse|null find($id, $lockMode = null, $lockVersion = null)
 * @method MembershipWarehouse|null findOneBy(array $criteria, array $orderBy = null)
 * @method MembershipWarehouse[]    findAll()
 * @method MembershipWarehouse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembershipWarehouseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MembershipWarehouse::class);
    }

    public function save(MembershipWarehouse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MembershipWarehouse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function showAssignedWarehouse(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT m.warehouse_id 
            FROM membership_warehouse m
            WHERE m.user_id = :id 
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);

        return $resultSet->fetchAllAssociative();
    }

    public function showAssignedUser(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT m.user_id 
            FROM membership_warehouse m
            WHERE m.warehouse_id = :id 
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);

        return $resultSet->fetchAllAssociative();
    }
    

//    /**
//     * @return MembershipWarehouse[] Returns an array of MembershipWarehouse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MembershipWarehouse
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
