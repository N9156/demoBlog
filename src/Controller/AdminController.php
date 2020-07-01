<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/articles", name="admin_articles")
     */
    public function adminArticles(ArticleRepository $repo)
    {
        //on appelle getManager() qui est le gestionnaire d'entités de Doctrine. Il est responsable de l'enregistrement des objets et leur récuperations 
        //dans la base de données
        $em = $this->getDoctrine()->getManager();
        //getClassMetadata() permet de recolter les metadonnees d'une table SQL (noms des champs, cle primaire, type de champs) à
        //partir d'une entité/class
        //getFieldNames() permet de récupérer les noms des champs / colonnes d'une table SQL à partir d'une entité
        $colonnes = $em->getClassMetadata(Article::class)->getFieldNames();//donnees nom des colonnes
        
        $articles = $repo->findAll();
        dump($articles);
        dump($colonnes);
        return $this->render('admin/admin_articles.html.twig', [
            'articles' => $articles,
            'colonnes' => $colonnes
        ]);
    }
    /**
     * @Route("/admin/{id}/edit-article", name="admin_edit_article")
     */
    public function editArticle(Article $article)
    {
        dump($article);

        $form = $this->createForm(ArticleType::class, $article);
        return $this->render('admin/edit_article.html.twig',[
            'formEdit' => $form->createView()
        ]);
    }
}
