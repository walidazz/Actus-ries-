<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{

    // TODO: https://symfony.com/doc/current/the-fast-track/en/21-cache.html#adding-http-cache-headers  à regarder

    private $repo;
    private $cache;

    public function __construct(ArticleRepository $repo, CacheInterface $cache)
    {
        $this->repo  = $repo;
        $this->cache = $cache;
    }

    //TODO: https://www.grafikart.fr/tutoriels/tag-cache-twig-1236


    //https://symfony.com/doc/current/the-fast-track/fr/21-cache.html


    /**
     * @Route("/", name="homepage")
     */
    public function getHomepage()
    {


        return $this->render('article/homepage.html.twig');
    }


    // public function renderHomepage()
    // {
    //     $globales = $this->repo->findBy([], ['createdAt' => 'DESC'], 3);
    //     $series = $this->repo->findThreeLast('Séries');
    //     $films  = $this->repo->findThreeLast('Films');
    //     $animes = $this->repo->findThreeLast('Animés');
    //     $news   = $this->repo->findThreeLast('Actualités');
    // }

    /**
     * @Route("/article/{libelle}", name="article_category_list")
     */
    public function articlelList($libelle, PaginatorInterface $paginator, Request $request)
    {

        $query    = $this->repo->findAllByCategory($libelle);
        $articles = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );
        return $this->render('article/list.html.twig', compact('articles', 'libelle'));
    }

    /**
     * @Route("/tags/{libelle}", name="article_tag_list")
     */
    public function articlelListByTag($libelle, PaginatorInterface $paginator, Request $request)
    {
        $tag      = $libelle;
        $query    = $this->repo->findAllByTags($libelle);
        $articles = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('article/list.html.twig', compact('articles', 'libelle'));
    }

    /**
     * @Route("/article/show/{slug}", name="article_show")
     */
    public function show(Article $article, Request $request, EntityManagerInterface $em)
    {
        $sameCategory = $this->repo->findThreeLast($article->getCategory()->getLibelle());
        $news         = $this->repo->findBy([], ['createdAt' => 'DESC'], 3);

        $comment = new Comment();
        $form    = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $comment->setArticle($article)
                ->setAuteur($user);
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire posté !');
        }

        // $news = $this->cache->get('news', function () {
        //   return $this->repo->findBy([], ['createdAt' => 'DESC'], 3);
        // });

        return $this->render('article/index.html.twig', ['article' => $article, 'sameCategory' => $sameCategory, 'news' => $news, 'form' => $form->createView()]);
    }
}
