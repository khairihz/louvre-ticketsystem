<?php

namespace App\Repository;

use DateTimeInterface;
use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function getClientBooking(int $id): ?Booking
    {
        $qb = $this
            ->createQueryBuilder('b')
            ->innerJoin('b.tickets', 't')
            ->addSelect('t')
            ->where('b.id = :id')
            ->setParameter('id', $id)
        ;

        return $qb
            ->getQuery()
            ->getSingleResult();
    }

    public function getNumberOfTicketPerDay(DateTimeInterface $date): int
    {
        $qb = $this
            ->createQueryBuilder('b')
            ->select('SUM(b.numberOfTickets)')
            ->where('b.visit = :visit')
            ->setParameter('visit', $date)
        ;

        return $qb
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }
}
