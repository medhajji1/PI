<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Reclamation[] Returns an array of Reclamation objects
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

    /**
    * @return Reclamation[] Returns an array of Reclamation objects
     */

    public function tri($value): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.status = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id','DESC')
            ->getQuery()
            ->getResult()
        ;
   }
   public function findByNom($nom)
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.nom LIKE :nom')
        ->setParameter('nom', '%'.$nom.'%')
        ->getQuery()
        ->getResult();
}
public function getStatus(): array
{
    $query = $this->createQueryBuilder('r')
        ->select('r.status, COUNT(r.status) as count')
        ->groupBy('r.status')
        ->getQuery();

    return $query->getResult();
}
}
