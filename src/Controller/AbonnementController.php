<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Account;
use App\Entity\Category;
use App\Entity\Paiment;
use App\Form\AbonnementType;
use App\Form\SubscriptionFormType;
use App\Repository\AbonnementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;

use Symfony\Component\HttpFoundation\Session\SessionInterface;


class AbonnementController extends AbstractController
{
    
    public function getMembersPerCategory(AbonnementRepository $abonnementRepository)
    {

    }
    

    #[Route('/dashboard/abonnement', name: 'abonnementPage')]
    public function index(AbonnementRepository $abonnementRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dataStatistics =$this->getMembersPerCategory($abonnementRepository);//metier 
    
        


        $abonnements = $entityManager->getRepository(Abonnement::class)->findAll();
        $abonnement = new Abonnement();
        $form = $this->createForm(AbonnementType::class, $abonnement);



        //ban form
        $formBan = $this->createForm(SubscriptionFormType::class);//metier

        return $this->render('dashboard/abonnement/abonnement.html.twig', [
            'formBan' => $formBan->createView(),
            'form' => $form->createView(),
            'abonnements' => $abonnements,
            'abonnement' =>null,
            'modify' => false,
            'dataStatistics' => $dataStatistics,
        ]);
    }


    #[Route('/dashboard/abonnement/save/{id?}', name: 'save_abonnement')]
    public function save(Request $request, Abonnement $abonnement = null): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $abonnements = $entityManager->getRepository(Abonnement::class)->findAll();
        
        if (!$abonnement) {
            $modify=false;
            $abonnement = new Abonnement();
        }
        else{
            $modify=true;
        }
    
        $form = $this->createForm(AbonnementType::class, $abonnement);        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($abonnement);
            $entityManager->flush();
            return $this->redirectToRoute('abonnementPage');
        }
    
             //ban form
             $formBan = $this->createForm(SubscriptionFormType::class);//metier

        return $this->render('dashboard/abonnement/abonnement.html.twig', [
            'formBan' => $formBan->createView(),
            'form' => $form->createView(),
            'abonnements' => $abonnements,
            'abonnement' =>$abonnement,
            'modify' => $modify,
            'dataStatistics' => null,
        ]);
    }

    #[Route('/dashboard/abonnement/delete/{id}', name: 'delete_abonnement')]
    public function delete(Request $request, Abonnement $abonnement): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($abonnement);
            $entityManager->flush();
        return $this->redirectToRoute('abonnementPage');
    }


    #[Route('/dashboard/abonnement/filtred', name: 'filtre_abonnement')]
    public function filterAbonnements(Request $request,AbonnementRepository $abonnementRepository)
    {
        $minprix = $request->request->get('minprix');
        $maxprix = $request->request->get('maxprix');
        $minDuree = $request->request->get('minDuree');
        $maxDuree = $request->request->get('maxDuree');
        $entityManager = $this->getDoctrine()->getManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder
            ->select('a')
            ->from(Abonnement::class, 'a');
    
        if ($minprix != null) {
            $queryBuilder
                ->andWhere('a.prix >= :minprix')
                ->setParameter('minprix', $minprix);
        }
    
        if ($maxprix != null) {
            $queryBuilder
                ->andWhere('a.prix <= :maxprix')
                ->setParameter('maxprix', $maxprix);
        }
    
        if ($minDuree != null) {
            $queryBuilder
                ->andWhere('a.duree >= :minDuree')
                ->setParameter('minDuree', $minDuree);
        }
    
        if ($maxDuree != null) {
            $queryBuilder
                ->andWhere('a.duree <= :maxDuree')
                ->setParameter('maxDuree', $maxDuree);
        }
    
        $abonnements = $queryBuilder->getQuery()->getResult();

        // Render your template with the filtered abonnements

        $abonnement = new Abonnement();
        $form = $this->createForm(AbonnementType::class, $abonnement);
             //ban form
             $formBan = $this->createForm(SubscriptionFormType::class);
             $dataStatistics =$this->getMembersPerCategory($abonnementRepository);
        return $this->render('dashboard/abonnement/abonnement.html.twig', [
            'formBan' => $formBan->createView(),
            'form' => $form->createView(),
            'abonnements' => $abonnements,
            'abonnement' =>null,
            'modify' => false,
            'dataStatistics' => $dataStatistics,
        ]);
    }

    #[Route('/dashboard/abonnement/banMember', name: 'banMember')]
    public function banMember(Request $request , AbonnementRepository $abonnementRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $formBan = $this->createForm(SubscriptionFormType::class);
        $formBan->handleRequest($request);
    
        if ($formBan->isSubmitted() && $formBan->isValid()) {

            $data = $formBan->getData();
            $abonnementId = $data['abonnement'];
            $userId = $data['user'];
           
            $abonnement = $entityManager->getRepository(Abonnement::class)->find($abonnementId);
            $user = $entityManager->getRepository(Account::class)->find($userId);
            if ($abonnement) {
                $abonnement->removeUserIdFromMembers($userId);
                $entityManager->flush(); 
            }


            //sms 
            $message = "Hello dear ".$user->getName().", you have suspended from the subscription ".$abonnement->getNom() ." .";
            $this->sendSms($user->getPhonenumber(),"Fitlife",$message);
            return $this->redirectToRoute('abonnementPage');
        
        }







        $abonnements = $entityManager->getRepository(Abonnement::class)->findAll();

        $abonnement = new Abonnement();
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $dataStatistics =$this->getMembersPerCategory($abonnementRepository);

        return $this->render('abonnement/abonnement.html.twig', [
            'formBan' => $formBan->createView(),
            'form' => $form->createView(),
            'abonnements' => $abonnements,
            'abonnement' =>null,
            'modify' => false,
            'dataStatistics' => $dataStatistics,

        ]);
    }

    ///api sms function
    public function sendSms($phone,$brandName,$message){
        $basic  = new \Vonage\Client\Credentials\Basic("bb5f8701", "u9zRzmNvwDvjsRLg");
        $client = new \Vonage\Client($basic);

            $response = $client->sms()->send(
                new \Vonage\SMS\Message\SMS( $phone , $brandName, $message)
            );

            $message = $response->current();

            if ($message->getStatus() == 0) {
                echo "The message was sent successfully\n";
            } else {
                echo "The message failed with status: " . $message->getStatus() . "\n";
            }
        
        
        
    }





    //abonnement front 
    #[Route('/app/abonnement', name: 'abonnementsPage_Front')]
    public function abonnementPage(AbonnementRepository $abonnementRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $abonnements = $entityManager->getRepository(Abonnement::class)->findAll();
        
        return $this->render('app_front/abonnement/abonnement.html.twig', [
            'abonnements' => $abonnements,
        ]);
    }


    #[Route('/app/abonnement/Subscribe', name: 'Subscribe')]
    public function Subscribe(Request $request,AbonnementRepository $abonnementRepository, SessionInterface $session): Response
    {

        if ($session->has('accountuser')) {

            $cartname = $request->get('cartname');
            $cartcode = $request->get('cartcode');
            $abonnementid = $request->get('abonnementid');

            $user = $session->get('accountuser');
            $entityManager = $this->getDoctrine()->getManager();
            $abonnement = $entityManager->getRepository(Abonnement::class)->findOneBy(['id' => $abonnementid]);
            $abonnement->setMembers($abonnement->getMembers().$user->getId().",");
            $entityManager->persist($abonnement);
            $entityManager->flush();

            //add a new paiment (iduser,montant,cartname,cartcode,date of today )
            $montant = $abonnement->getPrix();
            $payment = new Paiment();
            $payment->setUser($user); 
            $payment->setMontant($montant); 
            $payment->setCartName($cartname); 
            $payment->setCartCode($cartcode);
            $payment->setDate(new \DateTime()); 
            
            // Get the entity manager
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($payment);
            $entityManager->flush();
            
            return $this->redirectToRoute('abonnementsPage_Front');
        }

            return $this->redirectToRoute('app_front_signin');
            
    }



    
}
