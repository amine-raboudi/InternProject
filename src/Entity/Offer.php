<?php

namespace App\Entity;

use App\Repository\OfferRepository;
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
}
