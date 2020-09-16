<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
    public function create(){
        $ad = new Ad();

        $form = $this -> createFormBuilder($ad)
                      -> add('title')
                      -> add('price')
                      -> add('introduction')
                      -> add('content')
                      -> add('coverImage')
                      -> add('rooms')
                      -> add('save', SubmitType::class, [
                          'label' => 'créer une nouvelle annonce ',
                          'attr'  => [ 'class' => 'btn btn-primary']
                      ])
                      -> getForm();


        return $this -> render('ad/new.html.twig',[
            "form" => $form->createView()
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
