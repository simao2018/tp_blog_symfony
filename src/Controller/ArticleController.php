<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\Paginator;
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
        // $em->persist($article);
        // $em->flush();

        $isArticle = true;

        $getArticle = $articleRepository->findAll();
        $db_query = $this->getDoctrine()->getRepository(Article::class)->findBy([], ['createdAt' => 'desc']);
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

        dump(sizeof($getArticle));

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $getArticle,
            'isArticle' => $isArticle,
            'pagination' => $db_articles
        ]);
    }
}
