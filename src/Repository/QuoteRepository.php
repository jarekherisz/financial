<?php

namespace App\Repository;

use App\Entity\Instrument;
use App\Entity\InstrumentExchange;
use App\Entity\Quote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quote>
 *
 * @method Quote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quote[]    findAll()
 * @method Quote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quote::class);
    }

    public function save(Quote $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Quote $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Instrument $instrument
     * @param \DateTimeInterface $date
     * @return Quote|null
     * @throws NonUniqueResultException
     */
    public function findOneByInstrumentAndDate(Instrument $instrument, DateTimeInterface $date): ?Quote
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.instrument = :instrument')
            ->andWhere('q.date = :date')
            ->setParameter('instrument', $instrument)
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult();
    }


    /**
     * @param InstrumentExchange $instrumentExchange
     * @return Quote[]
     */
    public function findArrayByInstrumentExchange(InstrumentExchange $instrumentExchange): array
    {
        $quotes = $this->createQueryBuilder('q')  // 'q' is an alias for 'Quote'
        ->where('q.instrumentExchange = :instrumentExchange')
            ->setParameter('instrumentExchange', $instrumentExchange)
            ->getQuery()
            ->getResult();


        $quotesArray = [];
        foreach($quotes as $quote) {
            $quotesArray[$quote->getDate()->format('Y-m-d')] = $quote;
        }

        return $quotesArray;
    }
}
