<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdType extends AbstractType
{

    /**
     * this function returns a label and placeholder for element text type
     *
     * @param [string] $label
     * @param [string] $placeholder
     * @return array containing label and attribute
     */
    private function getConfiguration($label, $placeholder)
    {
       return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this -> getConfiguration(
                'Titre', 'tapez un super titre pour votre annoce '
            ))
            ->add('slug', TextType::class, $this -> getConfiguration(
                'Chaine URL','adress web qui sera visible en allant voir votre annonce'
            ))
            ->add('coverImage', UrlType::class, $this -> getConfiguration(
                'Url de l\'image principale', 'Donnez une image qui donne envie !'
            ))
            ->add('introduction', TextType::class, $this -> getConfiguration(
                'Introduction', 'Donner une introduction pour votre annonce'
            ))
            ->add('content', TextareaType::class, $this -> getConfiguration(
               'Desctiption globale','Dennez une description qui donnera envie de venir chez vous!' 
            ))
            ->add('price',   MoneyType::class, $this -> getConfiguration(
                'Prix par nuit', 'Donner un prix !'
            ))
            ->add('rooms', IntegerType::class, $this -> getConfiguration(
                'Nobre de chambre', 'Entrez le nombre de chmbre que vous avez !'
            ))
            ->add('save', SubmitType::class, [
                'label' => 'créer une nouvelle annonce ',
                'attr'  => [ 'class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
