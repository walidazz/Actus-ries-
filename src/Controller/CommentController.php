<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

 /**
  * @Route("/comment/delete/{id}", name="comment_delete")
  * @Security("is_granted('ROLE_ADMIN') or user == comment.getAuteur()", message="Vous n'étes pas autorisé à faire cette action")
  */
 public function delete(Comment $comment, EntityManagerInterface $em, Request $request)
 {
  $referer = $request->headers->get('referer');
  if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
   $em->remove($comment);
   $em->flush();
   $this->addFlash('success', 'Commentaire supprimé!');
  }
  return $this->redirect($referer);
 }
}
