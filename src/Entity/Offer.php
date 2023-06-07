<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OfferRepository::class)
 */
class Offer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $Price;

    /**
     * @ORM\Column(type="string")
     */
    private $DateStart;

    /**
     * @ORM\Column(type="string")
     */
    private $DateEnd;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryOffer::class, inversedBy="offers")
     */
    private $Category;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsActive;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="Offer")
     */
    private $reservations;

    /**
     * @ORM\ManyToOne(targetEntity=Agent::class, inversedBy="offers")
     */
    private $agent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Title;

    /**
     * @ORM\Column(type="string", length=2550)
     */
    private $description;

    /**
     * @ORM\Column(type="json")
     */
    private $images;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getDateStart(): ?string
    {
        return $this->DateStart;
    }

    public function setDateStart(string $DateStart): self
    {
        $this->DateStart = $DateStart;

        return $this;
    }

    public function getDateEnd(): ?string
    {
        return $this->DateEnd;
    }

    public function setDateEnd(string $DateEnd): self
    {
        $this->DateEnd = $DateEnd;

        return $this;
    }

    public function getCategory(): ?CategoryOffer
    {
        return $this->Category;
    }

    public function setCategory(?CategoryOffer $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->IsActive;
    }

    public function setIsActive(bool $IsActive): self
    {
        $this->IsActive = $IsActive;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setOffer($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getOffer() === $this) {
                $reservation->setOffer(null);
            }
        }

        return $this;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;

          }

    public function setImages(?array $images): void
    {
        $this->images = $images;

    }
}
