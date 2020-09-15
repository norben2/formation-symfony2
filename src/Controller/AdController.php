<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * Undocumented function
     *
     * @return void
     * @Route("/ads/{slug}", name = "ads_show")
     */
    public function show($slug, AdRepository $repo){
        /* chercher l'annonce en utilisant le slug
        on peut juste utiliser la fonction de doctrine 
        findOneBy qui retourne une valeur contrairement à findby 
        et lui rejouter le champ de l'entité qu'on veut trouver
        ic on rejout slug pour faire findOneBySlug
        donc il va chercher dans Ad.php et retourner le resultat de la 
        fonction getSlug, car il va chercher slug, puis il va retourner la 
        valeur de la fonction get qui lui correspond
        */
        $ad = $repo -> findOneBySlug($slug);

        return $this -> render("ad/show.html.twig", [
            "ad" => $ad
        ]);



    }
}
