<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistance\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
     * @Route("/blog/{id}/edit",name="blog_edit")
     */
    ///bloc/new genere une insertion
    //blog/edit pour modification
    public function form(Article $article =null,Request $request, EntityManagerInterface $manager)//25 06 11:38 methode qui permet de supprimer inserer des données
    //request stock les données saisies
    {
        //dump($request);
        /*if($request->request->count() > 0)
        {
            $article = new Article;
            $article->setTitle($request->request->get('title'))
                     ->setContent($request->request->get('content'))
                     ->setImage($request->request->get('image'))
                     ->setCreatedAt(new DateTime());
            $manager->persist($article);
            $manager->flush();//excute l'insertion en bdd
            dump($article);
            return $this->redirectToRoute('blog_show',[
                    'id'=> $article->getId()
            ]);

        }*/
        /*->add('title',TextType::class, [
                         'attr' =>[ 
                             'placeholder'=>"Saisir le titre de l'article",
                             'class' => "col-md-6 mx-auto"
                         ]
                     ])*/
        //autre methode de création de formulaire
        //$article =new Article;//17h
        /*$article->setTitle("Titre Titre prérempli")
                ->setContent("Contenu prérempli");*/
        if(!$article){//si article est null
            $article=new Article;
        }

        /*$form = $this->createFormBuilder($article)//this objet du controleur
                     ->add('title')
                     ->add('content')
                     ->add('image')
                     ->getForm();*///12 H 26 06 2020
        $form =$this->createForm(ArticleType::class,$article);//12 H 26 06 2020
        $form->handleRequest($request);//$request equivalent à $POST //methode qui envoie dans l'objet article les setters?handleRequest methode de l'objet form
        if($form->isSubmitted() && $form->isValid())
        {
            if(!$article->getId())
            {
                $article->setCreatedAt(new \DateTime);
            }
            

            $manager->persist($article);
            $manager->flush();
            dump($article);
            return $this->redirectToRoute('blog_show',[
                'id' =>$article->getId()
                ]);

        }
        return $this->render('blog/create.html.twig',[
            'formArticle' =>$form->createView(),
            'editMode' =>$article->getId() !== null
        ]);
        dump($article);
    }//fin create  creation formulaire

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
