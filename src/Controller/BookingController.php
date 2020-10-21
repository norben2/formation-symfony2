<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * 
     * @Security("is_granted('ROLE_USER')",
     * message = "Désolé ! Vous devez être connecté pour résérver cette annonce.")
     */
    public function book(Ad $ad, Request $request, ObjectManager $manager)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $booking->setBooker($user)
                    ->setAd($ad);
            
            // si les dates ne sont pas disponibles, message d'erreur
            if(!$booking->isBoockableDates()){
                $this->addFlash(
                    'warning',
                    'les dates que vous avez choisi, sont prise !'
                );
            }else {
                 // si non enregistrement et redirection
                $manager->persist($booking);
                $manager->flush();
                //redirection 
                return $this->redirectToRoute('booking_show', [
                    'id' => $booking->getId(),
                    'withAlert'=> true]);
            }
           
        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * permet d'afficher la page d'une réservation
     * @Route("/booking/{id}", name = "booking_show")
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking){
        return $this->render('booking/show.html.twig',[
            'booking' => $booking
        ]);
    }
}
