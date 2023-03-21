<?php

namespace App\Entity;

use App\Repository\NewAdminRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NewAdminRepository::class)
 */
class NewAdmin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $MailSended=false;

    

    

    public function getId(): ?int
    {
        return $this->id;
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

    public function isMailSended(): ?bool
    {
        return $this->MailSended;
    }

    public function setMailSended(bool $MailSended): self
    {
        $this->MailSended = $MailSended;

        return $this;
    }

    

   
}
