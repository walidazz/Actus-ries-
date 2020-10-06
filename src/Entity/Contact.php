<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


class Contact
{

    private $id;

    /**
     *@Assert\NotBlank(message="Champ requis")
     *@Assert\Length(min="3" , max="40", minMessage="Votre texte est trop court", maxMessage="Votre texte est trop long")
     */
    private $firstName;

    /**
     *@Assert\NotBlank(message="Champ requis")
     *@Assert\Length(min="3" , max="40", minMessage="Votre texte est trop court", maxMessage="Votre texte est trop long")
     *
     */
    private $lastName;

    /**
     *@Assert\NotBlank(message="Champ requis")
     *@Assert\Length(min="5" , max="40", minMessage="Votre texte est trop court", maxMessage="Votre texte est trop long")
     */
    private $email;

    /**
     *@Assert\NotBlank(message="Champ requis")
     *@Assert\Length(min="10" , max="100", minMessage="Votre texte est trop court", maxMessage="Votre texte est trop long")
     *
     */
    private $motif;

    /**
     *@Assert\Length(min="10" , max="300", minMessage="Votre texte est trop court", maxMessage="Votre texte est trop long")
     *@Assert\NotBlank(message="Champ requis")
     *
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
