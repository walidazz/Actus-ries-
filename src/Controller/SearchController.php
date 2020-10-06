<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search/", name="search", methods={"GET"})
     */
    public function search(Request $request, ArticleRepository $repo, PaginatorInterface $paginator)
    {

        $search = $request->query->get('search');

        $query    = $repo->search($search);
        $articles = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );
        return $this->render('article/searchpage.html.twig', compact('articles'));
    }
}
