<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $userName = $utils->getLastUsername();
        return $this->render('admin/account/login.html.twig', [
             //retoutner true si une erreur existe 
             'hasError'=> $error !== null,
             'userName'=>$userName
        ]);
    }


    /**
     * permet de se déconnecter et de rediriger l'administrateur
     * 
     *  @Route("/admin/logout", name = "admin_account_logout")
     * 
     * @return void
     */
    public function logout(){
    }
}
