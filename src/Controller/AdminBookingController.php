<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Service\PaginationService;
use App\Repository\BookingRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_bookings_index")
     */
    public function index(BookingRepository $repo, $page,PaginationService $pagination)
    {
        
        $pagination->setEntity(Booking::class)
                   ->setCurrentPage($page);
        
        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * permet d'éditer une annonce
     * @Route("/admin/bookings/{id}/edit", name = "admin_booking_edit")
     *
     * @return Response
     */
    public function edit(Request $request, Booking $booking, ObjectManager $manager){
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isvalid()){
            //recalculer le pricx si update
            // $booking->setAmount($booking->getDuration() * $booking->getAd->getPrice());
            // recalculer le prix si update, on mettant le prix à 0 le preUpdate va recalculer le 
            //montant dans booking.php
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "la réservation n°{$booking->getId()} a bien été modifié"
            );
            return $this->redirectToRoute("admin_bookings_index");
        }
        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * permet de supprimer une réservation
     * @Route("/admin/bookings/{id}/delete", name = "admin_booking_delete")
     * @return Response
     */
    public function delete(Booking $booking, ObjectManager $manager){
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "la réservation à bien été supprimé"
        );  
        return $this->redirectToRoute('admin_bookings_index');
    }
}
