<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
{
 /**
  * @ORM\Id()
  * @ORM\GeneratedValue()
  * @ORM\Column(type="integer")
  */
 private $id;

 /**
  * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
  * @ORM\JoinColumn(nullable=false)
  */
 private $auteur;

 /**
  * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="comments")
  * @ORM\JoinColumn(nullable=false)
  */
 private $article;

 /**
  * @ORM\Column(type="text")
  * @Assert\Length(
  *      max = 250,
  *      maxMessage = "Commentaire trop long",
  * )
  */
 private $content;

 /**
  * @ORM\Column(type="datetime")
  */
 private $createdAt;

 /**
  * @ORM\OneToMany(targetEntity=ReportComment::class, mappedBy="reportedComment", orphanRemoval=true)
  */
 private $reportComments;

 public function __construct()
 {
  $this->reportComments = new ArrayCollection();
 }

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

 public function getArticle(): ?Article
 {
  return $this->article;
 }

 public function setArticle(?Article $article): self
 {
  $this->article = $article;

  return $this;
 }

 public function getContent(): ?string
 {
  return $this->content;
 }

 public function setContent(string $content): self
 {
  $this->content = $content;

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

 public function __toString()
 {
  return $this->content;
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

 /**
  * @return Collection|ReportComment[]
  */
 public function getReportComments(): Collection
 {
  return $this->reportComments;
 }

 public function addReportComment(ReportComment $reportComment): self
 {
  if (!$this->reportComments->contains($reportComment)) {
   $this->reportComments[] = $reportComment;
   $reportComment->setReportedComment($this);
  }

  return $this;
 }

 public function removeReportComment(ReportComment $reportComment): self
 {
  if ($this->reportComments->contains($reportComment)) {
   $this->reportComments->removeElement($reportComment);
   // set the owning side to null (unless already changed)
   if ($reportComment->getReportedComment() === $this) {
    $reportComment->setReportedComment(null);
   }
  }

  return $this;
 }
}
