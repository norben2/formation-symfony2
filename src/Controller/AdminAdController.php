<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdFormType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntity(Ad::class)
                   ->setCurrentPage($page);
   

        return $this->render('admin/ad/index.html.twig', [
            'pagination'   => $pagination
        ]);
    }

    /**
     * permet d'éditer une annonce
     * @Route("/admin/ads/{id}/edit", name = "admin_ads_edit")
     *
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager){
        $form = $this->createForm(AdFormType::class, $ad);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> à bien été modifié"
            );
        }

        return $this->render("admin/ad/edit.html.twig", [
            "ad" => $ad,
            "form" => $form->createView()
        ]);
    }

    /**
     * permet de supprimer une annonce
     * @Route("/admin/ads/{id}/delete", name = "admin_ads_delete")
     *
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager){
        $bookings  = $ad->getBookings();
        if(\count($bookings) > 0){
            $this->addFlash(
                'warning',
                "l'annonce {$ad->getTitle()} ne peut pas être supprimé, car elle contient des réservations" 
            );
        }else {
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "l'annonce <strong>{$ad->getTitle()} </strong> a bien été supprimée"
            );
        }
        return $this->redirectToRoute("admin_ads_index");
    }
}
