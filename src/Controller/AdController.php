<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdFormType;
use App\Repository\AdRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdController extends Controller
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repository)
    {
        // $repository = $this -> getDoctrine()-> getRepository(Ad::class);

        $ads = $repository -> findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

     /**
     * créer une nouvelle annonce
     *
     * @return Response
     * 
     * @Route("/ads/new", name = "ads_create")
     */
    public function create(Request $request, ObjectManager $manager){
        $ad = new Ad( );
        
        $form = $this -> createForm(AdFormType::class,$ad);
        $form -> handleRequest($request);   

        if($form -> isSubmitted() && $form -> isValid()){
            // $manager = $this-> getDoctrine() -> getManager();

            foreach( $ad->getImages() as $image){
                $image -> setAd($ad);
                $manager -> persist($image);
            }
            $ad->setAuthor($this->getUser());

            $manager -> persist($ad);
            $manager -> flush();

         
            $this -> addFlash(
                'success',
                "L\'annonce <strong> {$ad -> getTitle()} </strong> a enregitré !"
            );

            return $this -> redirectTORoute('ads_show', [
                'slug' => $ad -> getSlug()
            ]);
        }
    
        return $this -> render('ad/new.html.twig',[
            "form" => $form->createView()
        ]);

    }
    /**
     * permet d'étiter les annonces
     * @Route("/ads/{slug}/edit", name= "ads_edit")
     *
     * @return Response
     */
    public function edit(Ad $ad, ObjectManager $manager, Request $request){

        $form = $this -> createForm(AdFormType::class,$ad);
        $form -> handleRequest($request);   

        if($form -> isSubmitted() && $form -> isValid()){
            foreach( $ad->getImages() as $image){
                $image -> setAd($ad);
                $manager -> persist($image);
            }
            $manager-> persist($ad);
            $manager-> flush();

            $this->addFlash(
                'success',
                "L\'annonce <strong> {$ad -> getTitle()} </strong> a été modifié avec succés !"
            );

            return $this -> redirectTORoute('ads_show', [
                'slug' => $ad -> getSlug()
            ]);
        }

        return $this -> render('ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
     * @Route("/ads/{slug}", name = "ads_show")
     */
    public function show(Ad $ad)
    {
    
        return $this -> render("ad/show.html.twig", [
            "ad" => $ad
        ]);
    }
}
