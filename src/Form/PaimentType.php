<?php

namespace App\Form;

use App\Entity\Paiment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaimentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false, 
            ])
            ->add('cartname', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false, 
            ])
            ->add('cartcode', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false, 
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiment::class,
        ]);
    }
}
