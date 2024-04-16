<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductType extends AbstractType
{

    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false, 
            ])
            ->add('ref', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false, 
            ])
            ->add('price', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false, 
            ])
            ->add('quantity', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false, 
            ])
            ->add('weight', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false, 
            ])
            ->add('imageUrl', FileType::class, [
                'data_class' => null,
                'label' => 'Product Image',
                'mapped' => true, // Change this to true to allow Symfony to map this field to a property
                'required' => false, // Make the file upload optional if needed
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'rows' => 3],
                'required' => false, 
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary'],
            ]);            
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
