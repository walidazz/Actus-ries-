<?php

namespace App\Twig;

use Twig\Environment;
use Twig\TwigFunction;
use App\Repository\ArticleRepository;
use Twig\Extension\AbstractExtension;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;

class HomepageExtension extends AbstractExtension
{

    private $cache;
    private $repo;
    private $twig;

    public function __construct(CacheInterface $cache, ArticleRepository $repo, Environment $twig)
    {

        $this->cache = $cache;
        $this->repo = $repo;
        $this->twig = $twig;
    }



    public function getFunctions()
    {
        return [
            //methode appelé par twig
            new TwigFunction('homepage', [$this, 'getHomepage'], ['is_safe' => ['html']])
        ];
    }

    //  $data = $this->cache->get('homepage', function (ItemInterface $item) {
    //    $item->expiresAfter(24 * 3600);


    public function getHomepage()
    {

        return $this->cache->get('homepage', function (ItemInterface $item) {
            $item->expiresAfter(24 * 3600);

            return $this->getRenderHomepage();
        });
    }




    private function  getRenderHomepage()
    {

        $globales = $this->repo->findBy([], ['createdAt' => 'DESC'], 3);
        $series = $this->repo->findThreeLast('Séries');
        $films = $this->repo->findThreeLast('Films');
        $animes = $this->repo->findThreeLast('Animés');
        $news = $this->repo->findThreeLast('Actualités');

        return $this->twig->render('shared/_homepage.html.twig', [
            'globales' => $globales,
            'series' => $series,
            'films' => $films,
            'animes' => $animes,
            'news' => $news
        ]);
    }
}
