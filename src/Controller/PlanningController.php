<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Planning;
use App\Form\PlanningType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PlanningController extends AbstractController
{
    #[Route('/dashboard/plannings', name: 'planningsPage')]
    public function index(): Response
    {

        $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);

        $entityManager = $this->getDoctrine()->getManager();
        $plannings = $entityManager->getRepository(Planning::class)->findAll();


        // Fetch coach entities based on planning IDs

        return $this->render('dashboard/planning/planning.html.twig', [
            'planning' => null,
            'form' => $form->createView(),
            'plannings' => $plannings,
            'modify' => false,
        ]);
    }

    #[Route('/dashboard/planning/save/{id?}', name: 'save_planning')]
    public function save(Request $request, Planning $planning = null): Response
    {
        if (!$planning) {
            $modify=false;
            $planning = new Planning();
        }
        else{
            $modify=true;
        }
    
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($planning);
            $entityManager->flush();
            return $this->redirectToRoute('planningsPage');
        }
    
        $entityManager = $this->getDoctrine()->getManager();
        $plannings = $entityManager->getRepository(Planning::class)->findAll();




        
        return $this->render('dashboard/planning/planning.html.twig', [
            'planning' => $planning,
            'form' => $form->createView(),
            'plannings' => $plannings,
            'modify' => $modify,
        ]);
    }

    #[Route('/dashboard/planning/delete/{id}', name: 'delete_planning')]
    public function delete(Request $request, Planning $planning): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($planning);
            $entityManager->flush();
            
        return $this->redirectToRoute('planningsPage');
        
    }


    // page  planning  (interface coach)
    

    
        #[Route('/dashboard/coach/plannings', name: 'planningCoach')]
        public function planningCoach(SessionInterface $session): Response
        {
   
            $Coach =  $session->get('account');
            $entityManager = $this->getDoctrine()->getManager();
            $plannings = $entityManager->getRepository(Planning::class)->findBy(['coach' => $Coach]);    

            return $this->render('dashboard/planning/planningCoach.html.twig', [
                'plannings' => $plannings,
                'coach' => $Coach,
            ]);
        }


}
