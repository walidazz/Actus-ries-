<?php

namespace App\tests\Entity;

use App\Entity\Article;
use PHPUnit\Framework\TestCase;



class ArticleTest extends TestCase
{
    public function testDouble()
    {
        $article = new Article();
        $article->double(2);
        $this->assertEquals(4, $article->double(2));
    }
}
