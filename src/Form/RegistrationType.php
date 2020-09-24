<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstName', TextType::class, $this->getConfiguration("Prénom", "Votre prénom...")
                )
            ->add(
                'lastName', TextType::class, $this->getConfiguration("Nom", "Votre nom...")
                )
            ->add(
                'email',  EmailType::class, $this->getConfiguration("Email", "Votre adress email...")
            )
            ->add(
                'picture',
                UrlType::class,
                $this->getConfiguration("Photo de profile","Url de votre avatare...")
                )            
            ->add(
                'hash',
                PasswordType::class,
                $this->getConfiguration("Mot de pass", "Donner un bon mot de pass...")
                )
            ->add(
                'introduction',
                TextType::class,
                $this->getConfiguration("Introduction","Présenter vous en quelques mots ...")
                )
            ->add(
                'description',
                TextType::class,
                $this->getConfiguration("Description détaillée", "C'est le moment de vous présenter en détails !")
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
