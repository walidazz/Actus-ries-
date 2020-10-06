<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 * fields={"email"},
 * message="email déja utilisée")
 * @Vich\Uploadable
 */
class User implements UserInterface, Serializable
{
 /**
  * @ORM\Id()
  * @ORM\GeneratedValue()
  * @ORM\Column(type="integer")
  */
 private $id;

 /**
  * @ORM\Column(type="string", length=180, unique=true)
  * @Assert\NotBlank
  */
 private $email;

 /**
  * @ORM\Column(type="json")
  */
 private $roles = [];

 protected $captcha;

 /**
  * @var string The hashed password
  * @ORM\Column(type="string" , nullable=true)
  * @Assert\NotBlank
  */
 private $password;

 /**
  * @ORM\Column(type="string", length=255, nullable=true)
  */
 private $googleID;

 /**
  * @ORM\Column(type="string", length=255, nullable=true)
  */
 private $googleAccessToken;

 /**
  * @Assert\EqualTo(propertyPath="password",message="Les mots de passes ne correspondent pas !")
  */
 private $passwordConfirm;

 /**
  * @ORM\Column(type="string", length=255 , nullable=true)
  * @Assert\NotBlank
  */
 private $pseudo;

 /**
  * @ORM\Column(type="string", length=255, nullable=true)
  */
 private $sexe;

 /**
  * @ORM\Column(type="date", nullable=true)
  */
 private $birthday;

 /**
  * @ORM\Column(type="string", length=255, nullable=true)
  */
 private $avatar = 'standard.png';

 /**
  * @Vich\UploadableField(mapping="user_image", fileNameProperty="avatar")
  */
 private $imageFile;

 /**
  * @ORM\Column(type="string", length=255, nullable=true)
  */
 private $tokenConfirmation;

 /**
  * @ORM\Column(type="boolean", nullable=true)
  */
 private $enable = false;

 /**
  * @ORM\Column(type="datetime")
  */
 private $createdAt;

 /**
  * @ORM\OneToMany(targetEntity=Article::class, mappedBy="auteur")
  */
 private $articles;

 /**
  * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="auteur", orphanRemoval=true)
  */
 private $comments;

 /**
  * @ORM\Column(type="datetime", nullable=true)
  */
 private $updatedAt;

 /**
  * @ORM\OneToMany(targetEntity=ReportUser::class, mappedBy="reported", orphanRemoval=true)
  */
 private $reported;

 /**
  * @ORM\OneToMany(targetEntity=ReportUser::class, mappedBy="auteur", orphanRemoval=true)
  */
 private $reportAuteur;

 /**
  * @ORM\OneToMany(targetEntity=ReportArticle::class, mappedBy="auteur", orphanRemoval=true)
  */
 private $reportArticles;

 /**
  * @ORM\OneToMany(targetEntity=ReportComment::class, mappedBy="auteur")
  */
 private $reportComments;

 public function __construct()
 {
  $this->articles       = new ArrayCollection();
  $this->comments       = new ArrayCollection();
  $this->reported       = new ArrayCollection();
  $this->reportAuteur   = new ArrayCollection();
  $this->reportArticles = new ArrayCollection();
  $this->reportComments = new ArrayCollection();
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

 /** @see \Serializable::serialize() */
 public function serialize()
 {
  return serialize(array(
   $this->id,
   $this->email,
   $this->password,
   $this->avatar,

   // see section on salt below
   // $this->salt,
  ));
 }
 /** @see \Serializable::unserialize() */
 public function unserialize($serialized)
 {
  list(
   $this->id,
   $this->email,
   $this->password,
   $this->avatar

   // see section on salt below
   // $this->salt
  ) = unserialize($serialized);
 }

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

 /**
  * A visual identifier that represents this user.
  *
  * @see UserInterface
  */
 public function getUsername(): string
 {
  return (string) $this->email;
 }

 /**
  * @see UserInterface
  */
 public function getRoles(): array
 {
  $roles = $this->roles;
  // guarantee every user at least has ROLE_USER
  $roles[] = 'ROLE_USER';

  return array_unique($roles);
 }

 public function setRoles(array $roles): self
 {
  $this->roles = $roles;

  return $this;
 }

 /**
  * @see UserInterface
  */
 public function getPassword(): string
 {
  return (string) $this->password;
 }

 public function setPassword(string $password): self
 {
  $this->password = $password;

  return $this;
 }

 /**
  * @see UserInterface
  */
 public function getSalt()
 {
  // not needed when using the "bcrypt" algorithm in security.yaml
 }

 /**
  * @see UserInterface
  */
 public function eraseCredentials()
 {
  // If you store any temporary, sensitive data on the user, clear it here
  // $this->plainPassword = null;
 }

 public function getPseudo(): ?string
 {
  return $this->pseudo;
 }

 public function setPseudo(string $pseudo): self
 {
  $this->pseudo = $pseudo;

  return $this;
 }

 public function getSexe(): ?string
 {
  return $this->sexe;
 }

 public function setSexe(?string $sexe): self
 {
  $this->sexe = $sexe;

  return $this;
 }

 public function getBirthday(): ?\DateTimeInterface
 {
  return $this->birthday;
 }

 public function setBirthday(?\DateTimeInterface $birthday): self
 {
  $this->birthday = $birthday;

  return $this;
 }

 public function getAvatar(): ?string
 {
  return $this->avatar;
 }

 public function setAvatar(?string $avatar): self
 {
  $this->avatar = $avatar;

  return $this;
 }

 public function getTokenConfirmation(): ?string
 {
  return $this->tokenConfirmation;
 }

 public function setTokenConfirmation(?string $tokenConfirmation): self
 {
  $this->tokenConfirmation = $tokenConfirmation;

  return $this;
 }

 public function getEnable(): ?bool
 {
  return $this->enable;
 }

 public function setEnable(?bool $enable): self
 {
  $this->enable = $enable;

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
  * @return Collection|Article[]
  */
 public function getArticles(): Collection
 {
  return $this->articles;
 }

 public function addArticle(Article $article): self
 {
  if (!$this->articles->contains($article)) {
   $this->articles[] = $article;
   $article->setAuteur($this);
  }

  return $this;
 }

 public function removeArticle(Article $article): self
 {
  if ($this->articles->contains($article)) {
   $this->articles->removeElement($article);
   // set the owning side to null (unless already changed)
   if ($article->getAuteur() === $this) {
    $article->setAuteur(null);
   }
  }

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
   $comment->setAuteur($this);
  }

  return $this;
 }

 public function removeComment(Comment $comment): self
 {
  if ($this->comments->contains($comment)) {
   $this->comments->removeElement($comment);
   // set the owning side to null (unless already changed)
   if ($comment->getAuteur() === $this) {
    $comment->setAuteur(null);
   }
  }

  return $this;
 }

 public function __toString()
 {
  return $this->pseudo;
 }

 /**
  * Get the value of passwordConfirm
  */
 public function getPasswordConfirm()
 {
  return $this->passwordConfirm;
 }

 /**
  * Set the value of passwordConfirm
  *
  * @return  self
  */
 public function setPasswordConfirm($passwordConfirm)
 {
  $this->passwordConfirm = $passwordConfirm;

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
  } else {
   $this->updatedAt = new \DateTime('now');
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
 public function initializeStatut()
 {
  if ((empty($this->roles)) && $this->enable === true) {
   $this->roles = ['ROLE_USER'];
  }
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
  * Get the value of captcha
  */
 public function getCaptcha()
 {
  return $this->captcha;
 }

 /**
  * Set the value of captcha
  *
  * @return  self
  */
 public function setCaptcha($captcha)
 {
  $this->captcha = $captcha;

  return $this;
 }

 /**
  * Get the value of googleID
  */
 public function getGoogleID()
 {
  return $this->googleID;
 }

 /**
  * Set the value of googleID
  *
  * @return  self
  */
 public function setGoogleID($googleID)
 {
  $this->googleID = $googleID;

  return $this;
 }

 /**
  * Get the value of googleAccessToken
  */
 public function getGoogleAccessToken()
 {
  return $this->googleAccessToken;
 }

 /**
  * Set the value of googleAccessToken
  *
  * @return  self
  */
 public function setGoogleAccessToken($googleAccessToken)
 {
  $this->googleAccessToken = $googleAccessToken;

  return $this;
 }

 /**
  * @return Collection|ReportUser[]
  */
 public function getReported(): Collection
 {
  return $this->reported;
 }

 public function addReported(ReportUser $reported): self
 {
  if (!$this->reported->contains($reported)) {
   $this->reported[] = $reported;
   $reported->setReported($this);
  }

  return $this;
 }

 public function removeReported(ReportUser $reported): self
 {
  if ($this->reported->contains($reported)) {
   $this->reported->removeElement($reported);
   // set the owning side to null (unless already changed)
   if ($reported->getReported() === $this) {
    $reported->setReported(null);
   }
  }

  return $this;
 }

 /**
  * @return Collection|ReportUser[]
  */
 public function getReportAuteur(): Collection
 {
  return $this->reportAuteur;
 }

 public function addReportAuteur(ReportUser $reportAuteur): self
 {
  if (!$this->reportAuteur->contains($reportAuteur)) {
   $this->reportAuteur[] = $reportAuteur;
   $reportAuteur->setAuteur($this);
  }

  return $this;
 }

 public function removeReportAuteur(ReportUser $reportAuteur): self
 {
  if ($this->reportAuteur->contains($reportAuteur)) {
   $this->reportAuteur->removeElement($reportAuteur);
   // set the owning side to null (unless already changed)
   if ($reportAuteur->getAuteur() === $this) {
    $reportAuteur->setAuteur(null);
   }
  }

  return $this;
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
   $reportArticle->setAuteur($this);
  }

  return $this;
 }

 public function removeReportArticle(ReportArticle $reportArticle): self
 {
  if ($this->reportArticles->contains($reportArticle)) {
   $this->reportArticles->removeElement($reportArticle);
   // set the owning side to null (unless already changed)
   if ($reportArticle->getAuteur() === $this) {
    $reportArticle->setAuteur(null);
   }
  }

  return $this;
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
   $reportComment->setAuteur($this);
  }

  return $this;
 }

 public function removeReportComment(ReportComment $reportComment): self
 {
  if ($this->reportComments->contains($reportComment)) {
   $this->reportComments->removeElement($reportComment);
   // set the owning side to null (unless already changed)
   if ($reportComment->getAuteur() === $this) {
    $reportComment->setAuteur(null);
   }
  }

  return $this;
 }

}
