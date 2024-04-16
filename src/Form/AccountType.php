<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [ 
                'attr' => ['class' => ' form-control'],
            ]) 
            ->add('prenom', TextType::class, [ 
                'attr' => ['class' => ' form-control'],
            ]) 
            ->add('age', TextType::class, [ 
                'attr' => ['class' => ' form-control'],
            ]) 
            ->add('mail', TextType::class, [ 
                'attr' => ['class' => ' form-control'],
            ]) 
            ->add('motpasse', TextType::class, [ 
                'attr' => ['class' => ' form-control'],
            ]) 
            ->add('title', TextType::class, [ 
                'attr' => ['class' => ' form-control'],
            ]) 
            ->add('phonenumber', TextType::class, [ 
                'attr' => ['class' => ' form-control'],
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
            'data_class' => Account::class,
        ]);
    }
}