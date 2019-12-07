<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(groups={"reservation"}, payload={"severity": "error"})
     * @Assert\NotNull(groups={"reservation"}, payload={"severity": "error"})
     * @Assert\Type("string", groups={"reservation"}, payload={"severity": "error"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\NotBlank(groups={"reservation"}, payload={"severity": "error"})
     * @Assert\NotNull(groups={"reservation"}, payload={"severity": "error"})
     * @Assert\Date(groups={"reservation"}, payload={"severity": "error"})
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type("bool", groups={"reservation"}, payload={"severity": "error"})
     */
    private $reduceRate;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Type("integer", groups={"reservation"}, payload={"severity": "error"})
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Booking", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBirthdate(): ?DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getReduceRate(): ?bool
    {
        return $this->reduceRate;
    }

    public function setReduceRate(bool $reduceRate): self
    {
        $this->reduceRate = $reduceRate;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(?Booking $booking): self
    {
        $this->booking = $booking;

        return $this;
    }
}
