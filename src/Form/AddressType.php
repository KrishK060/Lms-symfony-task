<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\City;
use App\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'placeholder' => 'Choose a country',
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choices' => $options['cities'] ?? [],
                'choice_label' => 'name',
                'placeholder' => 'Choose a city',
                'required' => true,
            ])
            ->add('SteetName')
            ->add('PostCode');
    }
    
    
        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
        //     $form = $event->getForm();
        //     $data = $event->getData();
            
        //     if ($data && $data->getCountry()) {
        //         $cities = $data->getCountry()->getCities();
        //         $form->add('city', EntityType::class, [
        //             'class' => City::class,
        //             'choices' => $cities,
        //             'choice_label' => 'name',
        //             'placeholder' => 'Choose a city',
        //             'mapped' => true,
        //             'required' => true,
        //         ]);
        //     }
        // });
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'cities' => [], // Add this line
        ]);
    }
}
