<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdFormType extends ApplicationType
{    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                 $this -> getConfiguration('Titre', 'tapez un super titre pour votre annoce ')
                 )
            ->add(
                'slug',
                 TextType::class, 
                 $this -> getConfiguration('Chaine URL','adress web qui sera visible en allant voir votre annonce(automatique)', [
                     'required' => false
                 ])
                 )
            ->add(
                'coverImage',
                 UrlType::class,
                  $this -> getConfiguration('Url de l\'image principale', 'Donnez une image qui donne envie !')
                  )
            ->add(
                'introduction',
                 TextType::class,
                  $this -> getConfiguration('Introduction', 'Donner une introduction pour votre annonce')
                  )
            ->add(
                'content',
                 TextareaType::class, 
                 $this -> getConfiguration('Desctiption globale','Dennez une description qui donnera envie de venir chez vous!')
                 )       
            ->add(
                'rooms', 
                IntegerType::class, 
                $this -> getConfiguration('Nobre de chambre', 'Entrez le nombre de chambres que vous avez !')
                )
            ->add(
                'price',
                 MoneyType::class,
                 $this -> getConfiguration('Prix par nuit', 'Donner un prix !')
                 )
            ->add(
                'images',
                 CollectionType::class,
                    [
                        'entry_type' => ImageType::class,
                        'allow_add' => true,
                        'allow_delete' => true
                    ]
            )
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
