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
}
