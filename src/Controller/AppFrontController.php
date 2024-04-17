<?php

namespace App\Controller;

use App\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AppFrontController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    
    #[Route('/app', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('app_front/index.html.twig', [
        ]);
    }
    #[Route('/app/signin', name: 'app_front_signin')]
    public function signin(): Response
    {
        return $this->render('app_front/signin.html.twig', [
        ]);
    }


    #[Route('/app/login', name: 'app_front_login')]
    public function login(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('mail');
            $password = $request->request->get('password');
            
            $entityManager = $this->getDoctrine()->getManager();
            $account = $entityManager->getRepository(Account::class)->findOneBy(['mail' => $email]);
            
    
            if ($account && $password==$account->getMotpasse() && $account->getTitle()=="user") {
                $this->session->set('accountuser', $account);
                $this->session->set('panier', [] );
                return $this->redirectToRoute('app_front');
            } else {
                // Authentication failed, display an error message or redirect back to the login page
                return $this->redirectToRoute('app_front_signin');
            }
        }
        return $this->render('app_front/signin.html.twig');
    }


    
    #[Route('/app/login', name: 'save_client')]
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Check if the request method is POST
        if ($request->isMethod('POST')) {
            // Create a new Account entity
            $client = new Account();
            $client->setTitle("user");
            
            // Set entity properties based on form data
            $client->setName($request->request->get('firstName'));
            $client->setPrenom($request->request->get('lastName'));
            $client->setAge($request->request->get('age'));
            $client->setMail($request->request->get('email'));
            $client->setMotpasse($request->request->get('password')); 
            $client->setPhonenumber($request->request->get('phoneNumber'));
    
            // Persist the entity to the database
            $entityManager->persist($client);
            $entityManager->flush();
    
            // Redirect to the homepage or any other appropriate route
            return $this->redirectToRoute('app_front');
        }
    
        // If the request method is not POST, render the sign-up form
        return $this->render('app_front/index.html.twig');
    }
    
    
    
    


}
