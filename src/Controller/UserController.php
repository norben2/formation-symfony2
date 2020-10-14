<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{slug}", name="user_show")
     * 
     * on va utiliser le param converter de symfony pour avoir l'utilisateur 
     * on argument de la fonction 
     */
    public function index(User $user)
    {
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
