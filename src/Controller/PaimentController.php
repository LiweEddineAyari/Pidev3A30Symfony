<?php

namespace App\Controller;

use App\Entity\Paiment;
use App\Form\PaimentType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaimentController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function getMonthlyIncome(): array
    {
        $entityManager = $this->getDoctrine()->getManager();
        $payments = $entityManager->getRepository(Paiment::class)->findAll();
    
        // Initialize an array to store income for each month
        $monthlyIncome = [
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0,
            'August' => 0,
            'September' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0,
        ];
    
        // Loop through payments and calculate total income for each month
        foreach ($payments as $payment) {
            $month = $payment->getDate()->format('F'); // Get the full month name (e.g., January)
            $monthlyIncome[$month] += $payment->getMontant(); // Add payment amount to the corresponding month
        }
    
        return $monthlyIncome;
    }
    

    #[Route('/dashboard/paiment', name: 'paimentPage')]
    public function index(): Response
    {
        $monthlyIncome = $this->getMonthlyIncome();

        // Prepare data for the chart
        $statisticsData = [];
        foreach ($monthlyIncome as $month => $income) {
            $statisticsData[] = ['month' => $month, 'income' => $income];
        }

        $paiment = new Paiment();
        $form = $this->createForm(PaimentType::class, $paiment);

        $entityManager = $this->getDoctrine()->getManager();
        $paiments = $entityManager->getRepository(Paiment::class)->findAll();

        return $this->render('dashboard/paiment/paiment.html.twig', [
            'paiment' => null,
            'form' => $form->createView(),
            'paiments' => $paiments,
            'modify' => false,
            'statisticsData' => $statisticsData,
        ]);
    }


    #[Route('/dashboard/paiment/save/{id?}', name: 'save_paiment')]
    public function save(Request $request, Paiment $paiment = null): Response
    {
        if (!$paiment) {
            $modify=false;
            $paiment = new Paiment();
        }
        else{
            $modify=true;
        }
    
        $form = $this->createForm(PaimentType::class, $paiment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($paiment);
            $entityManager->flush();
            return $this->redirectToRoute('paimentPage');
        }
    
        $entityManager = $this->getDoctrine()->getManager();
        $paiments = $entityManager->getRepository(Paiment::class)->findAll();
        
        $monthlyIncome = $this->getMonthlyIncome();

        // Prepare data for the chart
        $statisticsData = [];
        foreach ($monthlyIncome as $month => $income) {
            $statisticsData[] = ['month' => $month, 'income' => $income];
        }

        
        return $this->render('dashboard/paiment/paiment.html.twig', [
            'paiment' => $paiment,
            'form' => $form->createView(),
            'paiments' => $paiments,
            'modify' => $modify,
            'statisticsData' => $statisticsData,
        ]);
    }

    #[Route('/dashboard/paiment/delete/{id}', name: 'delete_paiment')]
    public function delete(Request $request, Paiment $paiment): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($paiment);
            $entityManager->flush();
            
        return $this->redirectToRoute('paimentPage');
    }



}
