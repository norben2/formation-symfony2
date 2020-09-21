<?php

namespace App\DataFixtures;

use Faker\Factory;
use \App\Entity\Ad;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        $genders = ['male', 'female'];

        // Gérer les utilisateurs
        $users = [];

        for($i = 1; $i < 10; $i++){

            $user = new User();
            $content =  '<p>'.join('<p></p>',$faker ->paragraphs(3)).'</p>';
            $gender = $faker->randomElement($genders);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99).'.jpg';

            $picture .= ($gender=='male'?'men/':'women/').$pictureId;

  
            $user->setFirstName($faker->firstname($gender))
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription($content)
                 ->setHash('password')
                 ->setPicture($picture);

                 $manager->persist($user);
                 $users[] = $user;

        }

        // Gérer les annonces
        for($i =1; $i <= 30; $i++){
            
            $title = $faker -> sentence();
            $coverImage  = $faker -> imageUrl();
            $introduction = $faker -> paragraph(2);
            $content =  '<p>'.join('<p></p>',$faker ->paragraphs(5)).'</p>';

            $user = $users[\mt_rand(0, count($users)-1)];

            $ad = new Ad();
            $ad -> setTitle($title)
                -> setPrice(\mt_rand(40, 200))
                -> setIntroduction($introduction)
                -> setContent($content)
                -> setCoverImage($coverImage)
                -> setRooms(\mt_rand(1,5))
                -> setAuthor($user);

                for($j =1; $j <= \mt_rand(2,5); $j++){
                    $image = new Image();
                    $image -> setUrl($faker -> imageUrl())
                           -> setCaption($faker -> sentence())
                           -> setAd($ad);
                    
                    $manager->persist($image);

                }
            
             // $product = new Product();
             // $manager->persist($product);
             $manager->persist($ad);
        } 
         $manager->flush();
    }
}
