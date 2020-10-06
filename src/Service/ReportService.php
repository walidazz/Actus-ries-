<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\ReportUser;
use App\Entity\ReportArticle;
use App\Entity\ReportComment;
use App\Repository\ReportUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReportArticleRepository;
use App\Repository\ReportCommentRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ReportService extends AbstractController
{
    private User $userConnected;

    private $motif;

    private $message;

    private EntityManagerInterface $em;

    private ValidatorInterface $validator;

    private $ReportUserRepo;

    private $ReportArticleRepo;

    private $ReportCommentRepo;

    private $request;

    public function __construct(Security $security, RequestStack $request, ReportUserRepository $reportUserRepo, ValidatorInterface $validator, ReportArticleRepository $reportArticleRepo, ReportCommentRepository $reportCommentRepo, EntityManagerInterface $em)
    {
        $this->userConnected = $security->getUser();
        $this->motif = $request->getCurrentRequest()->get('_motif');
        $this->message = $request->getCurrentRequest()->get('_message');
        $this->ReportUserRepo = $reportUserRepo;
        $this->validator = $validator;
        $this->ReportArticleRepo = $reportArticleRepo;
        $this->ReportCommentRepo = $reportCommentRepo;
        $this->em = $em;

        $this->request = $request->getCurrentRequest();
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @return bool
     */
    public function reportUserExist(User $user)
    {
        return  $this->ReportUserRepo->findBy(['auteur' => $this->userConnected, 'reported' => $user]);
    }

    public function reportArticleExist(Article $article)
    {
        return $this->ReportArticleRepo->findBy(['auteur' => $this->userConnected, 'reportedArticle' => $article]);
    }

    public function reportCommentExist(Comment $comment)
    {
        return  $this->ReportCommentRepo->findBy(['auteur' => $this->userConnected, 'reportedComment' => $comment]);
    }



    /**
     * Créer un nouvelle objet Assert/Length
     *
     * @param array $array
     * @return void
     */
    public function getLenghtConstraint(array $array)
    {
        return new Assert\Length($array);
    }

    /**
     * Permet de validater une contrainte Assert
     *
     * @param Assert $lenghtConstraint
     * @return void
     */
    public function validateContraints(
        $lenghtConstraint
    ) {
        return
            $this->validator->validate(
                $this->message,
                $lenghtConstraint
            );
    }


    /**
     * Créer un objet ReportUser et le persist dans la base de donnée
     *
     * @param Assert $lenghtConstraint
     * @param user $user
     * @return void
     */
    public function createUserReport($lenghtConstraint, user $user)
    {
        if ($this->isCsrfTokenValid('reportUser' . $user->getId(), $this->request->get('_token'))) {
            if (0 === count($this->validateContraints($lenghtConstraint))) {
                if (!$this->reportUserExist($user)) {
                    $report = new ReportUser();
                    $report->setAuteur($this->userConnected);
                    $report->setReported($user);
                    $report->setMessage($this->message);
                    $report->setMotif($this->motif);
                    $this->em->persist($report);
                    $this->em->flush();
                    $this->addFlash('success', 'Signalement pris en compte ! ');
                } else {
                    $this->addFlash('warning', 'Vous avez déja signalé cet utilisateur ! ');
                }
            } else {
                $this->addFlash('warning', 'Texte trop long ! ');
            }
        } else {
            $this->addFlash('warning', 'token non valide ');
        }
    }

    /**
     * Créer un objet ReportArticle et le persist dans la base de donnée
     *
     * @param [type] $lenghtConstraint
     * @param Article $article
     * @return void
     */
    public function createArticleReport($lenghtConstraint, Article $article)
    {
        if ($this->isCsrfTokenValid('reportArticle' . $article->getId(), $this->request->get('_token'))) {
            if (0 === count($this->validateContraints($lenghtConstraint))) {
                if (!$this->reportArticleExist($article)) {
                    $report = new ReportArticle();
                    $report->setAuteur($this->userConnected);
                    $report->setReportedArticle($article);
                    $report->setMessage($this->message);
                    $report->setMotif($this->motif);
                    $this->em->persist($report);
                    $this->em->flush();
                    $this->addFlash('success', 'Signalement pris en compte ! ');
                } else {
                    $this->addFlash('warning', 'Vous avez déja signalé cet article ! ');
                }
            } else {
                $this->addFlash('warning', 'Texte trop long ! ');
            }
        } else {
            $this->addFlash('warning', 'token non valide ');
        }
    }

    /**
     * Créer un objet reportComment et le persist dans la base de donnée
     *
     * @param [type] $lenghtConstraint
     * @param Comment $comment
     * @return void
     */
    public function createCommentReport($lenghtConstraint, Comment $comment)
    {
        if ($this->isCsrfTokenValid('reportComment' . $comment->getId(), $this->request->get('_token'))) {
            if (0 === count($this->validateContraints($lenghtConstraint))) {
                if (!$this->reportCommentExist($comment)) {
                    $report = new ReportComment();
                    $report->setAuteur($this->userConnected);
                    $report->setReportedComment($comment);
                    $report->setMessage($this->message);
                    $report->setMotif($this->motif);
                    $this->em->persist($report);
                    $this->em->flush();
                    $this->addFlash('success', 'Signalement pris en compte ! ');
                } else {
                    $this->addFlash('warning', 'Vous avez déja signalé ce commentaire ! ');
                }
            } else {
                $this->addFlash('warning', 'Texte trop long ! ');
            }
        } else {
            $this->addFlash('warning', 'token non valide ');
        }
    }
}
