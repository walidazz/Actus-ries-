<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Service\ReportService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReportController extends AbstractController
{

  // https://benoitgrisot.fr/inserer-un-formulaire-dans-une-modale-avec-symfony-et-materializecss/

  //TODO: mettre des token crsf dans les 3 formulaires de report

  /**
   * @Route("/report", name="report")
   */
  public function index()
  {
    return $this->render('report/index.html.twig', [
      'controller_name' => 'ReportController',
    ]);
  }

  /**
   * @Route("/report/article/{id}/", name="report_article", methods={"POST"})
   */
  public function reportArticle(Article $article, ReportService $reportService, Request $request)
  {

    $lenghtConstraint = $reportService->getLenghtConstraint(['max' => 250]);
    $reportService->createArticleReport($lenghtConstraint, $article);

    return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()]);
  }

  /**
   * @Route("/report/user/{id}/", name="report_user", methods={"POST"})
   */
  public function reportUser(User $user, ReportService $reportService, Request $request)
  {

    $lenghtConstraint = $reportService->getLenghtConstraint(['max' => 250]);

    $reportService->createUserReport($lenghtConstraint, $user);

    return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
  }

  /**
   * @Route("/report/comment/{id}/", name="report_comment", methods={"POST"})
   */
  public function reportComment(Comment $comment, ReportService $reportService, Request $request)
  {

    $lenghtConstraint = $reportService->getLenghtConstraint(['max' => 250]);
    $reportService->createCommentReport($lenghtConstraint, $comment);

    return $this->redirectToRoute('article_show', ['slug' => $comment->getArticle()->getSlug()]);
  }
}
