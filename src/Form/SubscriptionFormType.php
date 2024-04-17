<?php

namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionFormType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //abonnement
        $abonnements = $this->entityManager->getRepository(Abonnement::class)->findAll();
        $abonnementChoices = [];

        foreach ($abonnements as $abonnement) {
            $abonnementChoices[$abonnement->getNom()] = $abonnement->getId();
        }
        //abonnement
        $accounts = $this->entityManager->getRepository(Account::class)->findBy(['title' => 'user']);
        $userChoices = [];

        foreach ($accounts as $user) {
            $usersChoices[$user->getName()] = $user->getId();
        }

        $builder
        // Use dynamic product choices
            ->add('abonnement', ChoiceType::class, [
                'label' => 'Select Abonnement',
                'choices' => $abonnementChoices,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('user', ChoiceType::class, [
                'label' => 'Select User',
                'choices' => $usersChoices,
                'attr' => ['class' => 'form-control'],
            ])

            ->add('Ban', SubmitType::class, [
                'label' => 'Ban',
                'attr' => ['class' => 'btn btn-dark'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
