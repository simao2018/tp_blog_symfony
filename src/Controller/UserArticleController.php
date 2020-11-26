<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserArticleController extends AbstractController {
    /**
     * @var ArticleRepository
     */
    private $repository;

    public function __construct(ArticleRepository $repository){
            $this->repository = $repository;

    }

    /**
     *
     * @Route("/user/article", name="user.article.index")
     * */
    public function index(PaginatorInterface $paginator,Request $req): Response
    {
        $articles = $this->repository->findAll();
        dump($this->getUser()->getId());
        $db_query = $this->getDoctrine()->getRepository(Article::class)->findBy([], ['createdAt' => 'desc']);
        $db_articles = $paginator->paginate(
            $db_query,
            $req->query->getInt('page', 1),
            6
        );
        return $this->render('user/a_article.html.twig',compact('db_articles'));
    }
    /**
     *
     *@Route("/user/article/create", name="article.new")
     */
    public function new(Request $request){
        $article = new Article();
        $form = $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($article);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user.article.index');
        }
        return $this->render('user/newArticle.html.twig',[
            'article' => $article,
            'form' => $form->createView()
        ]);
    }
    /**
     *
     * @Route("/user/article/{id}", name="user.article.edit",methods="GET|POST")
     */
    public function edit(Article $article,Request $request): Response
    {
        $form = $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user.article.index');
        }

        return $this->render('user/edit.html.twig',[
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/user/article/{id}", name="user.article.delete", methods="DELETE")
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Article $article, Request $request)
    {
        if($this->isCsrfTokenValid('delete'.$article->getId(), $request->get('_token'))){
            $this->getDoctrine()->getManager()->remove($article);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirectToRoute('user.article.index');
    }
}
