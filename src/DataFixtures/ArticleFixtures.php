<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)//injection de dépendance
    {
       for($i=1;$i<=10;$i++){
           $article= new Article;
           $article->setTitle("Titre de l'article n° $i ")
                   ->setContent("<p>Contenu de l'article n°$i</p>")
                   ->setImage("https://picsum.photos/200")
                   ->setCreatedAt(new \DateTime() );
            $manager->persist($article);//class object manager gere la requete
       }

        $manager->flush();//libere la requète d'insertion
    }
}
