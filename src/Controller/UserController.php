<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
 /**
  * @Route("/user", name="user")
  */
 public function index()
 {

  $user = $this->getUser();
  return $this->render('user/index.html.twig', [
   'user' => $user,
  ]);
 }

 /**
  * @Route("/profile/{id}", name="user_profile")
  * @IsGranted("ROLE_USER")
  */
 public function showProfile(User $user, UserRepository $repo)
 {
  $user = $repo->find($user->getId());

  if ($user) {
   return $this->render('user/index.html.twig', [
    'user' => $user,
   ]);
  }

  return $this->redirectToRoute('homepage');
 }

 /**
  * @Route("/profile/edit/{id}", name="profile_edit")
  * @IsGranted("ROLE_USER")
  */
 public function profile(User $user, Request $request, EntityManagerInterface $em)
 {
  $form = $this->createForm(UserProfileType::class, $user);
  $form->handleRequest($request);

  if ($form->isSubmitted() && $form->isValid()) {
   $em->persist($user);
   $em->flush();
   $this->addFlash('success', 'Modification effectué avec succès !');
   return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
   # code...
  }

  return $this->render('user/profileType.html.twig', [
   'form' => $form->createView(),
  ]);
 }

 // /**
 //  * @Route("/profile/update-password", name="account_password")
 //  * @IsGranted("ROLE_USER")
 //  */
 // public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
 // {
 //     $user = $this->getUser();

 //     $passwordUpdate = new PasswordUpdate();
 //     $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
 //     $form->handleRequest($request);

 //     if ($form->isSubmitted() &&  $form->isValid()) {
 //         if ($encoder->isPasswordValid($user, $passwordUpdate->getOldPassword())) {
 //             $newPassword = $passwordUpdate->getNewPassword();
 //             $newHash =  $encoder->encodePassword($user, $newPassword);
 //             $user->setHash($newHash);
 //             $manager->persist($user);
 //             $manager->flush();
 //             $this->addFlash('success', ' mot de passe modifié avec succès !');
 //             return $this->redirectToRoute('annonces');
 //         } else {
 //             $form->get('oldPassword')->addError(new FormError("ce n'est pas votre mot de passe actuel"));
 //         }
 //     }
 //     return $this->render('account/password.html.twig', [
 //         'form' => $form->createView()
 //     ]);
 // }
}
