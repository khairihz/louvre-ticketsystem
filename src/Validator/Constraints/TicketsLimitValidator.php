<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class TicketsLimitValidator extends ConstraintValidator
{
    public const MAX_TICKET_PER_DAY = 1000;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * NotMoreThousandValidator constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Booking $booking
     */
    public function validate($booking, Constraint $constraint): void
    {
        $ticketsPerDay = $this->em
            ->getRepository(Booking::class)
            ->getNumberOfTicketPerDay($booking->getVisit());

        $ticketsLeft = self::MAX_TICKET_PER_DAY - $ticketsPerDay;

        if ($booking->getNumberOfTickets() >= $ticketsLeft) {
            $this->context->buildViolation($constraint->message)
                ->atPath('numberOfTickets')
                ->setParameter('{{ ticketsLeft }}', (string)(($ticketsLeft > 0) ? $ticketsLeft : 0))
                ->addViolation();
        }
    }
}
