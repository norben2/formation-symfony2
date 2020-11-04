<?php

namespace App\Service;

use Doctrine\Persistence\ObjectManager;

class StatsService {
    private $manager;

    public function __construct(ObjectManager $manager){
        $this->manager = $manager;
    }
    public function getStats(){
        $users = $this->getUsersCount();
        $ads = $this->getAdsCount();
        $bookings = $this->getBookingsCount();
        $comments = $this->getCommentsCount();
        return compact('users', 'ads', 'bookings', 'comments');

    }

    public function getAdsStats($order){
        return $this->manager->createQuery(
            "SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture 
            FROM App\Entity\Comment as c
            JOIN c.ad as a
            JOIN c.author as u
            GROUP BY a
            ORDER BY note $order"
            )
         ->setMaxResults(5)
        ->getResult();
    }

    public function getUsersCount(){
       return $this->manager->createQuery('SELECT Count(u) FROM App\Entity\User u')->getSingleScalarResult();
    }
    public function getAdsCount(){
        return $this->manager->createQuery('SELECT Count(a) FROM App\Entity\Ad a')->getSingleScalarResult();
    }
    public function getBookingsCount(){
        return $this->manager->createQuery('SELECT Count(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }
    public function getCommentsCount(){
        return $this->manager->createQuery('SELECT Count(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }
}