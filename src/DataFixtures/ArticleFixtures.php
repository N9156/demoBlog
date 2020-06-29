<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)//injection de dépendance
    {
       $faker = \Faker\Factory::create('fr_FR');
       //Creation de 3 categories
       for($i =1; $i<=3;$i++)
       {
           $category = new Category();
           $category->setTitle($faker->sentence())
                    ->setDescription($faker->paragraph());
           $manager->persist($category);

           //Creation entre 4 et 6 articles par categorie
           for($j =1 ;$j <= mt_rand(4,6); $j++)
           {
               $article = new Article;
               $content ='<p>'.join($faker->paragraphs(5), '</p><p>'). '</p>';
               $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);

                $manager->persist($article);
                //creation entre 4 et 10 commentaires par aricle
                for($k = 1; $k <=mt_rand(4,10); $k++)
                {
                    $comment = new Comment;
                    $content ='<p>'.join($faker->paragraphs(2), '</p><p>'). '</p>';

                    $now= new \Datetime;
                    $interval = $now->diff($article->getCreatedAt());//represente le temps en timestamp entre la date de creation de l'artcle et maintenant
                    $days = $interval->days; //nombre de jour entre la date de création de l'article et maintenant
                    $minimum = '-'.$days.'days';

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);

                    $manager->persist($comment);//on prepare l'insertion des commentaires

                }//fin for $k
           }//fin for $j

       }//fin for $i
       $manager->flush();//fait l'insertion en BDD
    }//fin load
}//fin class
