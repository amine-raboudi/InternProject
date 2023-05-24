<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="reservations")
     */
    private $Client;

    /**
     * @ORM\ManyToOne(targetEntity=Agent::class, inversedBy="reservations")
     */
    private $Agent;

    /**
     * @ORM\ManyToOne(targetEntity=Offer::class, inversedBy="reservations")
     */
    private $Offer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): self
    {
        $this->Client = $Client;

        return $this;
    }

    public function getAgent(): ?Agent
    {
        return $this->Agent;
    }

    public function setAgent(?Agent $Agent): self
    {
        $this->Agent = $Agent;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->Offer;
    }

    public function setOffer(?Offer $Offer): self
    {
        $this->Offer = $Offer;

        return $this;
    }
}
