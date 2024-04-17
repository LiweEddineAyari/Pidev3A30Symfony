<?php

namespace App\Form;
use App\Entity\Product;
use App\Entity\Exercises;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ExercisesType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
        // Use dynamic product choices
        ->add('product', EntityType::class, [
            'label' => 'Product',
            'class' => Product::class,
            'choice_label' => 'name', // Assuming 'name' is the property to display in the select options
            'attr' => ['class' => 'form-control'],
        ])
        ->add('name', TextType::class, [
            'label' => 'Name',
            'attr' => ['class' => 'form-control'],
            'required' => false,
        ])
        ->add('target', ChoiceType::class, [
            'label' => 'Target',
            'choices' => [
                'Chest' => 'Chest',
                'Back' => 'Back',
                'Shoulder' => 'Shoulder',
                'Arm' => 'Arm',
                'Abs' => 'Abs',
                'Leg' => 'Leg',
            ],
            'attr' => ['class' => 'form-control'],
        ])
        ->add('type', ChoiceType::class, [
            'label' => 'Type',
            'choices' => [
                'Growth' => 'Growth',
                'Strength' => 'Strength',
                'Cardio' => 'Cardio',
            ],
            'attr' => ['class' => 'form-control'],
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'attr' => ['class' => 'form-control', 'rows' => 3],
            'required' => false,
        ])
        ->add('img', FileType::class, [
            'data_class' => null,
            'label' => 'Exercise Image',
            'mapped' => true, // Change this to true to allow Symfony to map this field to a property
            'required' => false, // Make the file upload optional if needed
        ])
        ->add('intensity', ChoiceType::class, [
            'label' => 'Intensity',
            'choices' => [
                'Easy' => 'Easy',
                'Medium' => 'Medium',
                'Advanced' => 'Advanced',
            ],
            'attr' => ['class' => 'form-control'],
        ])
        ->add('equipmentneeded', ChoiceType::class, [
            'label' => 'Equipment Needed',
            'choices' => [
                'Yes' => 'Yes',
                'No' => 'No',
            ],
            'attr' => ['class' => 'form-control'],
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Save',
            'attr' => ['class' => 'btn btn-primary'],
        ]);  
}



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercises::class,
        ]);
    }
}
