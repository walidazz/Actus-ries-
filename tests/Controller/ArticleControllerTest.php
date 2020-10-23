<?php

namespace App\tests\Controller;

use App\Repository\TagRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{


    public function testHomepage()
    {

        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testShow()
    {

        $client = static::createClient();
        $articleRepository = static::$container->get(ArticleRepository::class);
        $testArticle = $articleRepository->findOneBy(['slug' => 'article-de-mahe-gerard0']);
        $client->request('GET', '/article/show/' . $testArticle->getSlug());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testArticlelList()
    {
        $client = static::createClient();
        $categoryRepository = static::$container->get(CategoryRepository::class);
        $testCategory = $categoryRepository->findOneBy(['libelle' => 'Films']);

        $client->request('GET', '/article/' . $testCategory->getLibelle());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }




    public function testArticlelListByTag()
    {

        $client = static::createClient();
        $tagRepository = static::$container->get(TagRepository::class);
        $testTag = $tagRepository->findOneBy(['libelle' => 'Horreur']);
        $client->request('GET', '/tags/' . $testTag->getLibelle());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}


// ./vendor/bin/simple-phpunit