<?php

namespace App\DataFixtures;

use Faker\Factory;
use \App\Entity\Ad;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        
        $adminRole = new Role();
        $adminRole->setTitle('ADMIN_ROLE');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Benkerrou')
                  ->setLastName('Noreddine')
                  ->setEmail('benkerrou.noreddine@gmail.com')
                  ->setPicture('/pics/adminPicture.jpg')
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>'.join('<p></p>',$faker ->paragraphs(3)).'</p>')   
                  ->addUserRole($adminRole);
        $manager->persist($adminUser);
        

        // Gérer les utilisateurs
        $users = [];
        $genders = ['male', 'female'];

        for($i = 1; $i < 10; $i++){

            $user = new User();
            $content =  '<p>'.join('<p></p>',$faker ->paragraphs(3)).'</p>';
            $gender = $faker->randomElement($genders);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99).'.jpg';

            $picture .= ($gender=='male'?'men/':'women/').$pictureId;

            $encoder = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname($gender))
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription($content)
                 ->setHash($encoder)
                 ->setPicture($picture);

                 $manager->persist($user);
                 $users[] = $user;

        }

        // Gérer les annonces
        for($i =1; $i <= 30; $i++){
            
            $title = $faker->sentence();
            // $coverImage  = $faker->imageUrl();
            $coverImage = "http://placeimg.com/640/360/".mt_rand(1, 1000);
            $introduction = $faker->paragraph(2);
            $content =  '<p>'.join('<p></p>',$faker->paragraphs(5)).'</p>';

            $user = $users[\mt_rand(0, count($users)-1)];

            $ad = new Ad();
            $ad -> setTitle($title)
                -> setPrice(\mt_rand(40, 200))
                -> setIntroduction($introduction)
                -> setContent($content)
                -> setCoverImage($coverImage)
                -> setRooms(\mt_rand(1,5))
                -> setAuthor($user);

                //créer les différentes images
                for($j =1; $j <= \mt_rand(2,5); $j++){
                    $image = new Image();
                    // $image -> setUrl($faker->imageUrl())
                    $image->setUrl("http://placeimg.com/640/360/".mt_rand(1, 1000))
                           -> setCaption($faker->sentence())
                           -> setAd($ad);
                    
                    $manager->persist($image);

                }
            
                // gestion de réservation 
                for($j = 1; $j <= \mt_rand(0,10); $j++){
                    $booking = new Booking();
                    $createdAt = $faker->dateTimeBetween('-6 months');
                    $startDate = $faker->dateTimeBetween('-3 months');
                    //create the duration of the booking
                    $duration = \mt_rand(3, 10);
                    // clone the sratDate so it will not be modified then add the duration to the startDate
                    $endDate = (clone $startDate)->modify("+ $duration days");
                    //calculate the amount
                    $amount = $ad->getPrice() * $duration;
                    //find a booker
                    $booker = $users[\mt_rand(0, count($users)-1)];
                    //créer un commentaire alératoire avec faker
                    $comment = $faker->paragraph();

                    $booking->setBooker($booker)
                            ->setAd($ad)
                            ->setStartDate($startDate)
                            ->setEndDate($endDate)
                            ->setCreatedAt($createdAt)
                            ->setAmount($amount)
                            ->setComment($comment);
                    $manager->persist($booking);

                    //gérer les commentaires, créer un commentaire au hazar pour une réservation au hazard
                    if(\mt_rand(0,1)){
                        $comment = new Comment();
                        $comment->setContent($faker->paragraph())
                                ->setRating(\mt_rand(0,5))
                                ->setAuthor($booker)
                                ->setAd($ad);
                        $manager->persist($comment);
                    }
                }


             // $product = new Product();
             // $manager->persist($product);
             $manager->persist($ad);
        } 
         $manager->flush();
    }
}
