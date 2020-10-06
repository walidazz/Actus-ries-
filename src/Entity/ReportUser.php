<?php

namespace App\Entity;

use App\Repository\ReportUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReportUserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class ReportUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reported")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reported;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reportAuteur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "Commentaire trop long",
     * )
     */
    private $motif;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReported(): ?User
    {
        return $this->reported;
    }

    public function setReported(?User $reported): self
    {
        $this->reported = $reported;

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

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

    public function setMessage(?string $message): self
    {
        $this->message = $message;

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

    /**
     * Permet d'initialiser la date de création !
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function initializeDate()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime('now');
        }
    }

    public function __toString()
    {
        return $this->motif;
    }
}
