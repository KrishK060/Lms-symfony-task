<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultipleCityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $cityNames = $options['data']['cities'] ?? [''];

        $builder
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'placeholder' => '-- Select Country --',
                'required' => true,
            ])
            ->add('cities', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => ['attr' => ['placeholder' => 'City name']],
                'by_reference' => false,
                'required' => true,
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
                'data' => true,
                'label' => 'Active',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // no data_class since this is a plain array form
            'data_class' => null,
        ]);
    }
}
