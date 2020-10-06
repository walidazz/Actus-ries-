<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\Table(name="article", indexes={@ORM\Index(columns={"title","content","introduction"},flags={"fulltext"})})
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Article
{
 /**
  * @ORM\Id()
  * @ORM\GeneratedValue()
  * @ORM\Column(type="integer")
  */
 private $id;

 /**
  * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
  * @ORM\JoinColumn(nullable=false)
  */
 private $auteur;

 /**
  * @ORM\Column(type="string", length=255)
  * @Groups({"userSimple"})
  */
 private $title;

 /**
  * @ORM\Column(type="string", length=255)
  */
 private $slug;

 /**
  * @ORM\Column(type="text", nullable=true)
  * @Groups({"userSimple"})
  */
 private $introduction;

 /**
  * @ORM\Column(type="text")
  * @Groups({"userSimple"})
  */
 private $content;

 /**
  * @ORM\Column(type="string", length=255, nullable=true)
  */
 private $image = 'standard.png';

 /**
  * @Vich\UploadableField(mapping="article_image", fileNameProperty="image")
  */
 private $imageFile;

 /**
  * @ORM\Column(type="datetime")
  */
 private $createdAt;

 /**
  * @ORM\Column(type="datetime", nullable=true)
  */
 private $updatedAt;

 /**
  * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="article", orphanRemoval=true)
  */
 private $comments;

 /**
  * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="article")
  */
 private $category;

 /**
  * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="article")
  */
 private $tags;

 /**
  * @ORM\OneToMany(targetEntity=ReportArticle::class, mappedBy="reportedArticle", orphanRemoval=true)
  */
 private $reportArticles;

 public function __construct()
 {
  $this->comments       = new ArrayCollection();
  $this->tags           = new ArrayCollection();
  $this->reportArticles = new ArrayCollection();
 }

 public function getImageFile(): ?File
 {
  return $this->imageFile;
 }

 public function setImageFile(?File $imageFile = null): self
 {
  $this->imageFile = $imageFile;

  if ($this->imageFile instanceof UploadedFile) {
   $this->updatedAt = new \DateTime('now');
  }
  return $this;
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

 public function getTitle(): ?string
 {
  return $this->title;
 }

 public function setTitle(string $title): self
 {
  $this->title = $title;

  return $this;
 }

 public function getSlug(): ?string
 {
  return $this->slug;
 }

 public function setSlug(string $slug): self
 {
  $this->slug = $slug;

  return $this;
 }

 public function getIntroduction(): ?string
 {
  return $this->introduction;
 }

 public function setIntroduction(?string $introduction): self
 {
  $this->introduction = $introduction;

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

 public function getImage(): ?string
 {
  return $this->image;
 }

 public function setImage(?string $image): self
 {
  $this->image = $image;

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

 public function getUpdatedAt(): ?\DateTimeInterface
 {
  return $this->updatedAt;
 }

 public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
 {
  $this->updatedAt = $updatedAt;

  return $this;
 }

 /**
  * @return Collection|Comment[]
  */
 public function getComments(): Collection
 {
  return $this->comments;
 }

 public function addComment(Comment $comment): self
 {
  if (!$this->comments->contains($comment)) {
   $this->comments[] = $comment;
   $comment->setArticle($this);
  }

  return $this;
 }

 public function removeComment(Comment $comment): self
 {
  if ($this->comments->contains($comment)) {
   $this->comments->removeElement($comment);
   // set the owning side to null (unless already changed)
   if ($comment->getArticle() === $this) {
    $comment->setArticle(null);
   }
  }

  return $this;
 }

 public function getCategory(): ?Category
 {
  return $this->category;
 }

 public function setCategory(?Category $category): self
 {
  $this->category = $category;

  return $this;
 }

 /**
  * @return Collection|Tag[]
  */
 public function getTags(): Collection
 {
  return $this->tags;
 }

 public function addTag(Tag $tag): self
 {
  if (!$this->tags->contains($tag)) {
   $this->tags[] = $tag;
   $tag->addArticle($this);
  }

  return $this;
 }

 public function removeTag(Tag $tag): self
 {
  if ($this->tags->contains($tag)) {
   $this->tags->removeElement($tag);
   $tag->removeArticle($this);
  }

  return $this;
 }

 /**
  * Permet d'initialiser le slug !
  *
  * @ORM\PrePersist
  * @ORM\PreUpdate
  *
  * @return void
  */
 public function initializeSlug()
 {
  if (empty($this->slug)) {
   $slugify    = new Slugify();
   $this->slug = $slugify->slugify($this->title);
  }
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
  } else {
   $this->updatedAt = new \DateTime('now');
  }
 }

 public function __toString()
 {
  return $this->title;
 }

 public function double($nombre): int
 {
  return $nombre * 2;
 }

 /**
  * @return Collection|ReportArticle[]
  */
 public function getReportArticles(): Collection
 {
  return $this->reportArticles;
 }

 public function addReportArticle(ReportArticle $reportArticle): self
 {
  if (!$this->reportArticles->contains($reportArticle)) {
   $this->reportArticles[] = $reportArticle;
   $reportArticle->setReportedArticle($this);
  }

  return $this;
 }

 public function removeReportArticle(ReportArticle $reportArticle): self
 {
  if ($this->reportArticles->contains($reportArticle)) {
   $this->reportArticles->removeElement($reportArticle);
   // set the owning side to null (unless already changed)
   if ($reportArticle->getReportedArticle() === $this) {
    $reportArticle->setReportedArticle(null);
   }
  }

  return $this;
 }

 /**
  * Permet de récupérer le commentaire d'un auteur par rapport à une annonce
  *
  * @return Article|null
  */
 public function getReportExist(User $auteur)
 {
  foreach ($this->reportArticles as $reportArticle) {
   if ($reportArticle->getAuteur() === $auteur) {
    return $reportArticle;
   }
   return null;

  }
 }



}
