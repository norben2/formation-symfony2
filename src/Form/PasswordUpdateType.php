<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'oldPassword',
                PasswordType::class,
                $this->getConfiguration(
                    'Ancien mot de pass',
                    'Saisir l\'ancien mot de pass'
                ))
            ->add(
                'newPassword',
                PasswordType::class,
                $this->getConfiguration(
                    'Nouveau mot de pass',
                    'Saisir le nouveau mot de pass'
                ))
            ->add(
                'confirmPassword',
                PasswordType::class,
                $this->getConfiguration(
                    'Confirmation du nouveau mot de pass',
                    'confirmer le nouveau mot de pass'
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
