<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


//admin
    #[Route('dashboard/account/admins', name: 'adminPage')]
    public function adminPage(): Response
    {
        $admin = new Account();
        $form = $this->createForm(AccountType::class, $admin);

        $entityManager = $this->getDoctrine()->getManager();
        $admins = $this->entityManager->getRepository(Account::class)->findBy(['title' => 'admin']);

        return $this->render('dashboard/account/admins.html.twig', [
            'admins' => $admins,
            'admin' => null,
            'form' => $form->createView(),
            'modify' => false,
        ]);
    }

    #[Route('/dashboard/account/admins/save/{id?}', name: 'save_admin')]
    public function save(Request $request, Account $admin = null): Response
    {
        if (!$admin) {
            $modify=false;
            $admin = new Account();
        }
        else{
            $modify=true;
        }
    
        $form = $this->createForm(AccountType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();
            return $this->redirectToRoute('adminPage');
        }
        
    

        $entityManager = $this->getDoctrine()->getManager();
        $admins = $this->entityManager->getRepository(Account::class)->findBy(['title' => 'admin']);


        
        return $this->render('dashboard/account/admins.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
            'admins' => $admins,
            'modify' => $modify,
        ]);
    }


    #[Route('/dashboard/admins/delete/{id}', name: 'delete_admin')]
    public function delete(Request $request, Account $admin): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($admin);
            $entityManager->flush();
            
        return $this->redirectToRoute('adminPage');
        
    }

    




    

//user 

#[Route('dashboard/account/users', name: 'usersPage')]
public function userPage(): Response
{
    $user = new Account();
    $form = $this->createForm(AccountType::class, $user);

    $entityManager = $this->getDoctrine()->getManager();
    $users = $this->entityManager->getRepository(Account::class)->findBy(['title' => 'user']);

    return $this->render('dashboard/account/users.html.twig', [
        'users' => $users,
        'user' => null,
        'form' => $form->createView(),
        'modify' => false,
    ]);
}

#[Route('/dashboard/account/users/save/{id?}', name: 'save_user')]
public function saveuser(Request $request, Account $user = null): Response
{
    if (!$user) {
        $modify=false;
        $user = new Account();
    }
    else{
        $modify=true;
    }

    $form = $this->createForm(AccountType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('usersPage');
    }
    


    $entityManager = $this->getDoctrine()->getManager();
    $users = $this->entityManager->getRepository(Account::class)->findBy(['title' => 'user']);


    
    return $this->render('dashboard/account/users.html.twig', [
        'user' => $user,
        'form' => $form->createView(),
        'users' => $users,
        'modify' => $modify,
    ]);
}



#[Route('/dashboard/users/delete/{id}', name: 'delete_user')]
public function deleteuser(Request $request, Account $user): Response
{
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        
    return $this->redirectToRoute('usersPage');
    
}

//coaches 

#[Route('dashboard/account/coaches', name: 'coachesPage')]
public function coachesPage(): Response
{
    $coach = new Account();
    $form = $this->createForm(AccountType::class, $coach);

    $entityManager = $this->getDoctrine()->getManager();
    $coaches = $this->entityManager->getRepository(Account::class)->findBy(['title' => 'coach']);

    return $this->render('dashboard/account/coaches.html.twig', [
        'coaches' => $coaches,
        'coach' => null,
        'form' => $form->createView(),
        'modify' => false,
    ]);
}

#[Route('/dashboard/account/coaches/save/{id?}', name: 'save_coach')]
public function savecoach(Request $request, Account $coach = null): Response
{
    if (!$coach) {
        $modify = false;
        $coach = new Account();
    } else {
        $modify = true;
    }

    $form = $this->createForm(AccountType::class, $coach);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($coach);
        $entityManager->flush();
        return $this->redirectToRoute('coachesPage');
    }

    $coaches = $this->entityManager->getRepository(Account::class)->findBy(['title' => 'coach']);

    return $this->render('dashboard/account/coaches.html.twig', [
        'coach' => $coach,
        'form' => $form->createView(),
        'coaches' => $coaches,
        'modify' => $modify,
    ]);
}



#[Route('/dashboard/coaches/delete/{id}', name: 'delete_coach')]
public function deletecoach(Request $request, Account $coach): Response
{
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($coach);
        $entityManager->flush();
        
    return $this->redirectToRoute('coachesPage');
    
}









    //tache yasmine hedhi ^^ 

    #[Route('/dashboard/coach/Clients', name: 'Clients_CoachInterface')]
    public function clientsPage_CoachInterface(): Response
    {

        $clients = $this->entityManager->getRepository(Account::class)->findBy(['title' => 'user']);

        return $this->render('dashboard/account/client_CoachInterface.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/app/coach', name: 'coachPage_Front')]
    public function coachPage_ClientInterface(): Response
    {

        $coaches = $this->entityManager->getRepository(Account::class)->findBy(['title' => 'coach']);

        return $this->render('app_front/coach/coach.html.twig', [
            'coaches' => $coaches,
        ]);
    }
    
    
}
