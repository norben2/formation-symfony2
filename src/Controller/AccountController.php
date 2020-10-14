<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\Passwordupdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
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


    /**
     * permet de modifier le profile
     *
     * @Route("/account/profile", name="account_profile")
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager){
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "les modifications ont éré enregistrés avec succes"
            );
        }
        return $this->render("account/profile.html.twig",[
            'form'=>$form->createView()
        ]);
    }


    /**
     * modifier le mot de passe
     *
     * @Route("account/password-update", name="account_password_update")
     * @return Response
     */
    public function updatePassword(Request $request,UserPasswordEncoderInterface $encoder, ObjectManager $manager){
        $user = $this->getUser();
        $passwordUpdate = new Passwordupdate();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // 1- vérifier que le mot de pass actuel est le même que celui de l'utlisateur
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez saisie n'est pas votre mot de pass actuel"));
                
                // $this->addFlash(
                //     'danger',
                //     'Le mot de passe que vous avez saisie ne corresponds pas à l\'encien '
                // );
            }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success',
                    'les modifications ont bien été enregistrées'
                );
                return $this->redirectToRoute('homepage');
            }
            $manager->persist($user);
            $manager->flush();
        }


        return $this->render('account/password.html.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * Permet d'afficher le compte de l'utiilisateru connécté
     * 
     * @Route("/account", name="account_index")
     *
     * @return void
     */
    public function myAccount(){
        $user = $this->getUser();

        return $this->render('user/index.html.twig',[
            'user' => $user
        ]);
    }
}
