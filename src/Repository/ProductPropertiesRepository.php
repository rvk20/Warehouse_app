<?php

namespace App\Repository;

use App\Entity\ProductProperties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductProperties>
 *
 * @method ProductProperties|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductProperties|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductProperties[]    findAll()
 * @method ProductProperties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductPropertiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductProperties::class);
    }

    public function save(ProductProperties $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductProperties $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getLastPropertiesId()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT max(id) FROM product_properties 
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return intval($resultSet->fetchOne()) + 1;
    }

//    /**
//     * @return ProductProperties[] Returns an array of ProductProperties objects
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

//    public function findOneBySomeField($value): ?ProductProperties
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
