<?php

namespace App\Entity;

use App\Repository\ReportCommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReportCommentRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class ReportComment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reportComments")
     */
    private $auteur;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="reportComments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reportedComment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motif;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "Commentaire trop long",
     * )
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

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getReportedComment(): ?Comment
    {
        return $this->reportedComment;
    }

    public function setReportedComment(?Comment $reportedComment): self
    {
        $this->reportedComment = $reportedComment;

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
