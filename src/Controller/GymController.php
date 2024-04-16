<?php

namespace App\Controller;

use App\Entity\Gym;
use App\Form\GymType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GymController extends AbstractController
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }



    #[Route('dashboard/Gym/gym', name: 'gymPage')]
    public function gymPage(): Response
    {
        $gym = new Gym();
        $form = $this->createForm(GymType::class, $gym);

        $entityManager = $this->getDoctrine()->getManager();
        $gyms = $this->entityManager->getRepository(Gym::class)->findAll();

        return $this->render('dashboard/Gym/gyms.html.twig', [
            'gyms' => $gyms,
            'gym' => null,
            'form' => $form->createView(),
            'modify' => false,
        ]);
    }

    #[Route('/dashboard/Gym/gyms/save/{id?}', name: 'save_gym')]
    public function save(Request $request, Gym $gym = null): Response
    {
        if (!$gym) {
            $modify=false;
            $gym = new Gym();
        }
        else{
            $modify=true;
        }
    
        $form = $this->createForm(GymType::class, $gym);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gym);
            $entityManager->flush();
            return $this->redirectToRoute('gymPage');
        }
        
    

        $entityManager = $this->getDoctrine()->getManager();
        $gyms = $this->entityManager->getRepository(Gym::class)->findAll();


        
        return $this->render('dashboard/Gym/gyms.html.twig', [
            'gym' => $gym,
            'form' => $form->createView(),
            'gyms' => $gyms,
            'modify' => $modify,
        ]);
    }


    #[Route('/dashboard/Gym/delete/{id}', name: 'delete_gym')]
    public function delete(Request $request, Gym $gym): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gym);
            $entityManager->flush();
            
        return $this->redirectToRoute('gymPage');
        
    }   


    // Front

    #[Route('/app/gym', name: 'gymPage_Front')]
    public function gymsPage_Front(Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $gyms = $entityManager->getRepository(Gym::class)->findAll();

      
        return $this->render('app_front/gym/gym.html.twig', [
            'gyms' => $gyms,
        ]);
    }

    #[Route('/dashboard/coach/gyms', name: 'Coach_gymInterface')]
    public function gymPage_CoachInterface(): Response
    {

        $gyms = $this->entityManager->getRepository(gym::class)->findAll();

        return $this->render('dashboard/Gym/client_gymInterface.html.twig', [
            'gyms' => $gyms,
        ]);
    }

}
