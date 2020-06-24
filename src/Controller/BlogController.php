<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Persistance\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)//injection de dependance
    {
        //$repo =$this->getDoctrine()->getRepository(Article::class);15:49
        $articles =$repo->findAll();
        dump($articles);

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' =>$articles // envoie sur le template index.html.twig' les articles selectionnés en BDD ($articles) que nous allons traiter avec le langage TWIG sur le template
        ]);
    }
    /**
     *  @Route("/", name="home")
     */
    public function home()
    {
        
        return $this->render('blog/home.html.twig', [
            'title' => 'Bienvenue sur le blog Symfony',
            'age' => 25
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_create")
     */
    public function create()
    {
        return $this->render('blog/create.html.twig');
    }

    //show() : methode permettant de voir le detail d'un article
    //1
    /**
     * @Route("/blog/{id}",name="blog_show")
     */
    //route parametree /blog/{id}
    //public function show($id)// id 1
    public function show(ArticleRepository $repo, $id)// 16:19
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class);//16:19
        $article= $repo->find($id);//variable de reception id 1 en argument de la methode
        dump($article);
        return $this->render('blog/show.html.twig',[
            'article' =>$article
        ]);
    }
    
}
