<?php

namespace App\Controller;

use App\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

class DashBoardController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    

    #[Route('/', name: 'app_dash_board_signin')]
    public function index(): Response
    {
        return $this->render('dashboard/signin.html.twig', [
            'controller_name' => 'DashBoardController',
        ]);
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function main(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashBoardController',
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $entityManager = $this->getDoctrine()->getManager();
            $account = $entityManager->getRepository(Account::class)->findOneBy(['mail' => $email]);
            
    
            if ($account && $password==$account->getMotpasse()) {
                $this->session->set('account', $account);
                return $this->redirectToRoute('dashboard');
            } else {
                // Authentication failed, display an error message or redirect back to the login page
                return $this->redirectToRoute('login');
            }
        }
        return $this->render('dashboard/signin.html.twig');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(Request $request): Response
    {
        $this->session->set('account', null);
        return $this->redirectToRoute('app_dash_board_signin');  
    }

    

}
