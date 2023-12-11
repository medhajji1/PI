<?php

namespace App\Repository;

use App\Entity\Paiement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Paiement>
 *
 * @method Paiement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paiement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paiement[]    findAll()
 * @method Paiement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paiement::class);
    }

    public function save(Paiement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Paiement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBySearchAndSort($search, $sort)
    {
        $qb = $this->createQueryBuilder('p');

// Search
        if ($search) {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('p.motif', ':search'),
                $qb->expr()->like('p.email', ':search')
            ))->setParameter('search', "%$search%");
        }

// Sort
        switch ($sort) {
            case 'montant_asc':
                $qb->orderBy('p.montant', 'ASC');
                break;
            case 'montant_desc':
                $qb->orderBy('p.montant', 'DESC');
                break;
            case 'date_asc':
                $qb->orderBy('p.date', 'ASC');
                break;
            case 'date_desc':
                $qb->orderBy('p.date', 'DESC');
                break;
            default:
                $qb->orderBy('p.idpaiement', 'ASC');
        }

        return $qb->getQuery()->getResult();

    }

}