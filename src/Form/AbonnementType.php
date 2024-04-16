<?php
namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'label' => 'Category',
                'class' => Category::class,
                'choice_label' => 'nom', // Assuming 'name' is the property to display in the dropdown
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false, 
            ])
            ->add('duree', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false, 
            ])
            ->add('prix', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false, 
            ])
            ->add('fidelite', CheckboxType::class, [
                'label' => 'Fidelite', // Optional label for the checkbox
                'required' => false, // Whether the field is required or not
                // You can add more options here as needed
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
            'data_class' => Abonnement::class,
        ]);
    }
}
