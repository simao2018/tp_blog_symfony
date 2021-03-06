<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $req): Response
    {
        $article = new Article;
        $em = $this->getDoctrine()->getManager();

        $getArticle = $articleRepository->findAll();
        $db_query = $this->getDoctrine()->getRepository(Article::class)->findBy(['validated'=>true], ['createdAt' => 'desc']);
        $db_articles = $paginator->paginate(
            $db_query,
            $req->query->getInt('page', 1),
            6
        );

        if (sizeof($getArticle) < 1) {
            $isArticle = false;
        } else {
            $isArticle = true;
        }

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $getArticle,
            'isArticle' => $isArticle,
            'pagination' => $db_articles
        ]);
    }
    /**
     * @Route("/article/{slug}/{id}", name="article.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show($slug, $id, ArticleRepository $articleRepository): Response
    {

        $article = $articleRepository->find($id);

        return $this->render("article/article.html.twig", [
            'article' => $article
        ]);
    }
}
