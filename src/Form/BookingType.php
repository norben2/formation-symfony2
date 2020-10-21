<?php
namespace App\Form;
use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startDate', 
                DateType::class,
                $this->getConfiguration(
                    "date d'arrivée",
                    "La date de votre arrivée",
                    ["widget" => "single_text"]
                ))
            ->add(
                'endDate',
                DateType::class,
                $this->getConfiguration(
                    'date de départ',
                    'la date de votre départ',
                    ["widget" => "single_text"]
                    
                ))
            ->add('comment',
            TextareaType::class,
            $this->getConfiguration(
                false,
                'Ecrivez un commentaire si vous voulez adresser un message particulier au booker',[
                    'required' => false
                ]
                
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
