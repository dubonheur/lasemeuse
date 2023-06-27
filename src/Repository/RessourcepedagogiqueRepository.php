<?php

namespace App\Repository;

use App\Entity\Ressourcepedagogique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ressourcepedagogique>
 *
 * @method Ressourcepedagogique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ressourcepedagogique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ressourcepedagogique[]    findAll()
 * @method Ressourcepedagogique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RessourcepedagogiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ressourcepedagogique::class);
    }

    public function save(Ressourcepedagogique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ressourcepedagogique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Ressourcepedagogique[] Returns an array of Ressourcepedagogique objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ressourcepedagogique
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
