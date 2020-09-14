<?php

namespace App\DataFixtures;

use Faker\Factory;
use \App\Entity\Ad;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');


        
        $title = $faker -> sentence();
        $coverImage  = $faker -> imageUrl(1000, 350);
        $introduction = $faker -> paragraph(2);
        $content =  '<p>'.join('<p></p>',$faker ->paragraphs(5)).'</p>';

        for($i =1; $i <= 30; $i++){
            $ad = new Ad();
            $ad -> setTitle($title)
                -> setPrice(\mt_rand(40, 200))
                -> setIntroduction($introduction)
                -> setContent($content)
                -> setCoverImage($coverImage)
                -> setRooms(\mt_rand(1,5));
            
             // $product = new Product();
             // $manager->persist($product);
             $manager->persist($ad);
        } 
         $manager->flush();
    }
}
