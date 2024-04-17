<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Planning;
use App\Entity\Account;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class PlanningType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        $categoriesChoices = [];

        foreach ($categories as $category) {
            $categoriesChoices[$category->getNom()] = $category->getNom();
        }

        $builder
            ->add('coach', EntityType::class, [
                'label' => 'Coach',
                'class' => Account::class,
                'choice_label' => 'name', // Assuming 'name' is the property to display in the select options
                'attr' => ['class' => 'form-control'],
                'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->andWhere('a.title = :title')
                        ->setParameter('title', 'coach');
                },
            ])
            ->add('titre', ChoiceType::class, [
                'label' => 'Category',
                'choices' => $categoriesChoices,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateDebut', TextType::class, [ 
                'attr' => ['class' => 'js-datepicker form-control'],
            ]) 
            ->add('dateFin', TextType::class, [
                'attr' => ['class' => 'js-datepicker form-control'],
            ])
            ->add('heureDebut')
            ->add('heureFin')
            ->add('description', TextareaType::class, [
                'label' => 'Description',
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
            'data_class' => Planning::class,
        ]);
    }
}

