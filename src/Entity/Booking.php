<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message= "La date doit être au bon format")
     * @Assert\GreaterThan("today", message = "La date d'arrivé doit être supérieur à aujourdh'hui",
     * groups = {"front"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message= "La date doit être au bon format")
     * @Assert\GreaterThan(propertyPath = "startDate",
     * message = "attention, la date d'arriver ne peut pas être supérieur à la date de départ.")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * permet d'initialiser le prix et la date de création
     *
     * @return void
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersiste(){
        if(empty($this->createdAt)){
            $this->createdAt = new DateTime();
        }

        if(empty($this->amount)){
            // prix de l'annonce * nobre de jours
            $price = $this->ad->getPrice();
            $days  = $this->getDuration();
            $this->amount = $days * $price;
        }
    }


    public function isBoockableDates(){
        // 1)- Connaitre les dates de résérvations impossibles
        $notAvailableDays = $this->ad->getNotAvailableDays();

        // 2)- Comparer la date de réservation de l'annonce avec ces dates impossibles
        $bookingdays = $this->getDays();

        //transformer le format des jours en chaînes de caractères pour les comparer facilement
        $formatDay = function($day){
            return $day->format('Y-m-d');
        };
        $days = array_map($formatDay, $bookingdays);

        $notAvailable = array_map($formatDay,$notAvailableDays);

        // 3)- Retourner une réponse true ou false
        foreach($days as $day){
            if(\in_array($day, $notAvailable)){
                return false;
            }
        }
        return true; 
    }

    /**
     * permet de récupérer un tableau de parramètres de type dateTime qui correspondes à la réservation
     *
     * @return array un tableau d'objets Datetime
     */
    public function getDays(){
        $result = range(
            $this->getStartDate()->getTimestamp(),
            $this->getEndDate()->getTimestamp(),
            24 * 60 * 60
        );
        $days = array_map(function($daytimestamp){
            return new \DateTime(date('Y-m-d', $daytimestamp));
        }, $result);

        return $days;
    }
    public function getDuration(){
        $diff =  $this->endDate->diff($this->startDate);
        return $diff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
