<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * permet d'afficher et de gérer le formulaire de connexion
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $userName = $utils->getLastUsername();
        \dump($error);
        return $this->render('account/login.html.twig', [
            //retoutner true si une erreur existe 
            'hasError'=> $error !== null,
            'userName'=>$userName
        ]);
    }
    
    /**
     * permet de se déconnecter
     * @Route("/logout", name= "account_logout")
     * @return void
     */
    public function logout(){
        //rien
    }

    /**
     * permet de s'inscrire
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        $hash = $encoder->encodePassword($user, $user->getPassword());
        $user->setHash($hash);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "votre compte à bien été créé ! vous pouvez maintenant vous connecter !"
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form'=> $form->createView()
        ]);
    }
}
