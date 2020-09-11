<?php 
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {
    /**
     * 
     * @return void
     * @Route("/hello/{name}/{age}", name= "hell")
     * @Route("/salut", name = "hello_base")
     * @Route("salut/{prenom}", name = "hello_prenom")
     */
    public function hell ($name = "anonyme", $age="0"){
//         return new Response("bonjour $name vous avez $age ans");
            return $this->render(
                'template_hello.html.twig',
                ['name' => $name,
                  'age' => $age
                ]);
    }
    /**
     * @Route("/", name ="homepage")
     */
    public function home() {
        $prenoms = ['noreddine' => 29, 'toto' => 26, 'tata' => 16]; 
        $age = 10;
        $minAge = 18;
        return $this -> render('home.html.twig',[
            'title' => "bonjour toto",
            'minAge' => $minAge,
            'age'   => $age,
            'tableau' => $prenoms
        ]);
    }
}

?>