<?php

namespace App\Form;

use App\Entity\Gym;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GymType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idUser', TextType::class, [ 
                'attr' => ['class' => ' form-control'],
            ])
            ->add('nom', TextType::class, [ 
                'attr' => ['class' => ' form-control'],
            ])
            ->add('clientNumber', TextType::class, [ 
                'attr' => ['class' => ' form-control'],
            ])
            ->add('coachNumber', TextType::class, [ 
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
            'data_class' => Gym::class,
        ]);
    }
}
