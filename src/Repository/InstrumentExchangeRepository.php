<?php

namespace App\Repository;

use App\Entity\InstrumentExchange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstrumentExchange>
 *
 * @method InstrumentExchange|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstrumentExchange|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstrumentExchange[]    findAll()
 * @method InstrumentExchange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstrumentExchangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstrumentExchange::class);
    }

    public function save(InstrumentExchange $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InstrumentExchange $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return InstrumentExchange[]
     */
    public function findByTicker($ticker): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.ticker = :val')
            ->setParameter('val', $ticker)
            ->getQuery()
            ->getResult()
            ;
    }
}
