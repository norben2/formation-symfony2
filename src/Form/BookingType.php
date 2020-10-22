<?php
namespace App\Form;
use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer){
        $this->transformer = new $transformer;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startDate', 
                TextType::class,
                $this->getConfiguration(
                    "date d'arrivée",
                    "La date de votre arrivée"
                ))
            ->add(
                'endDate',
                TextType::class,
                $this->getConfiguration(
                    'date de départ',
                    'la date de votre départ'
                    
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
        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
