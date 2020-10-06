<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Tag;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
 private $encoder;

 public function __construct(UserPasswordEncoderInterface $encoder)
 {
  $this->encoder = $encoder;
 }

 public function load(ObjectManager $manager)
 {

  $faker     = Factory::create('fr_FR');
  $genre     = ['homme', 'femme'];
  $slugify   = new Slugify();
  $tabRaison = ['Spam', 'Arnaque', 'Comportement irrespectueux', 'Racisme', 'Incitation à la haine'];

  $date  = new \DateTime();
  $admin = new User();
  $admin->setEmail('walidazzimani@gmail.com')
   ->setPassword($this->encoder->encodePassword($admin, 'sharingan.'))
   ->setCreatedAt(new \DateTime('now'))
   ->setBirthday($date->setDate(1994, 11, 06))
   ->setSexe('homme')
   ->setAvatar("standard.png")
   ->setPseudo('Admin')
   ->setRoles(['ROLE_ADMIN'])
   ->setEnable(true);
  $manager->persist($admin);

  $tabCategory = ['Films', 'Séries', 'Animés', 'Actualités'];
  $tabTag      = ['Horreur', 'Adulte', 'Science-Fiction'];
  $tabImages   = ['avenger.png', 'boruto.png', 'dark.png', 'got.png', 'killingeve.png', 'onepiece.png', 'strangerthings.png', 'viking.png', 'viking2.png', 'standard.png'];

  for ($f = 0; $f < count($tabCategory); $f++) {
   $category = new Category();
   $category->setLibelle($tabCategory[0 + $f]);
   $manager->persist($category);
   $categories[] = $category;
   # code...
  }

  for ($j = 0; $j < count($tabTag); $j++) {
   $tags = new Tag();
   $tags->setLibelle($tabTag[0 + $j]);
   $manager->persist($tags);
   $tagsTabs[] = $tags;
  }

  for (
   $h = 0;
   $h < 3;
   $h++
  ) {
   $user = new User();
   $user->setEmail($faker->email())
    ->setPassword($this->encoder->encodePassword($user, 'sharingan.'))
    ->setCreatedAt(new \DateTime('now'))
    ->setBirthday($date->setDate(1994, 11, 06))
    ->setSexe($genre[mt_rand(0, count($genre) - 1)])
    ->setAvatar("standard.png")
    ->setPseudo($faker->userName())
    ->setRoles(['ROLE_USER'])
    ->setEnable(true);
   $manager->persist($user);
   $users[] = $user;

   # code...
  }

  for ($w = 0; $w < 25; $w++) {

   $article = new Article();
   $article->setAuteur($users[mt_rand(0, count($users) - 1)])
    ->setContent($faker->paragraph())
    ->setCreatedAt(new \DateTime('now'))
    ->setIntroduction($faker->paragraph())
    ->setTitle('Article de ' . $article->getAuteur())
    ->setImage($tabImages[mt_rand(0, count($tabImages) - 1)])
    ->setSlug($slugify->slugify($article->getTitle() . $w))
    ->addTag($tagsTabs[mt_rand(0, count($tagsTabs) - 1)])
    ->setCategory($categories[mt_rand(0, count($categories) - 1)]);
   $manager->persist($article);

   $commentaire = new Comment();
   $commentaire->setArticle($article)
    ->setAuteur($users[mt_rand(0, count($users) - 1)])
    ->setContent($faker->paragraph())
    ->setCreatedAt(new \DateTime('now'));
   $manager->persist($commentaire);
  }
  $manager->flush();
 }
}
