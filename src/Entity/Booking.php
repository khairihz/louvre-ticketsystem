<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 */
class Booking
{
    public const MINIMUM_NUMBER_OF_TICKETS = 1;

    public const MAXIMUM_NUMBER_OF_TICKETS = 10;

    public const TYPE_OF_TICKET_DAY = 0;

    public const TYPE_OF_TICKET_HALF_DAY = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Date(message="validator.date.message")
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @Assert\Email(groups={"stepOne"})
     * @Assert\NotBlank(groups={"stepOne"})
     * @Assert\NotNull(groups={"stepOne"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @Assert\Type("integer", groups={"stepOne"})
     *
     * @ORM\Column(type="smallint")
     */
    private $typeOfTicket;

    /**
     * @Assert\NotBlank(groups={"stepOne"}, payload={"severity": "error"})
     * @Assert\NotNull(groups={"stepOne"}, payload={"severity": "error"})
     * @Assert\Date(groups={"stepOne"}, payload={"severity": "error"})
     * @Assert\GreaterThanOrEqual("today", groups={"stepOne"}, payload={"severity": "error"})
     *
     * @ORM\Column(type="datetime")
     */
    private $visit;

    /**
     * @Assert\NotBlank(groups={"stepOne"}, payload={"severity": "error"})
     * @Assert\NotNull(groups={"stepOne"}, payload={"severity": "error"})
     * @Assert\Type("integer", groups={"stepOne"}, payload={"severity": "error"})
     * @Assert\GreaterThanOrEqual(Booking::MINIMUM_NUMBER_OF_TICKETS, groups={"stepOne"}, payload={"severity": "error"})
     * @Assert\LessThanOrEqual(Booking::MAXIMUM_NUMBER_OF_TICKETS, groups={"stepOne"}, payload={"severity": "error"})
     *
     * @ORM\Column(type="integer")
     */
    private $numberOfTickets;

    /**
     * @Assert\Type("float", payload={"severity": "error"})
     *
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="booking", orphanRemoval=true)
     */
    private $tickets;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $transactionId;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTypeOfTicket(): ?int
    {
        return $this->typeOfTicket;
    }

    public function setTypeOfTicket(int $typeOfTicket): self
    {
        $this->typeOfTicket = $typeOfTicket;

        return $this;
    }

    public function getVisit(): ?DateTimeInterface
    {
        return $this->visit;
    }

    public function setVisit(DateTimeInterface $visit): self
    {
        $this->visit = $visit;

        return $this;
    }

    public function getNumberOfTickets(): ?int
    {
        return $this->numberOfTickets;
    }

    public function setNumberOfTickets(int $numberOfTickets): self
    {
        $this->numberOfTickets = $numberOfTickets;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setBooking($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getBooking() === $this) {
                $ticket->setBooking(null);
            }
        }

        return $this;
    }
}
