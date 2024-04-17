<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Paiment;
use App\Entity\Panier;
use App\Entity\Panierproduct;
use App\Entity\Product;
use App\Form\CommandesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandesController extends AbstractController
{

    #[Route('/dashboard/commandes', name: 'commandesPage')]
    public function index(): Response //affichage
    {

        $commande = new Commande();
        $form = $this->createForm(CommandesType::class, $commande);

        $entityManager = $this->getDoctrine()->getManager();
        $commandes = $entityManager->getRepository(Commande::class)->findAll();

        return $this->render('dashboard/commandes/commandes.html.twig', [
            'commande' => null,
            'form' => $form->createView(),
            'commandes' => $commandes,
            'modify' => false,
        ]);
    }


    #[Route('/dashboard/commandes/save/{id?}', name: 'save_commande')]
    public function save(Request $request, Commande $commande = null): Response
    {
        if (!$commande) {
            $modify=false;
            $commande = new Commande();
        }
        else{
            $modify=true;
        }
    
        $form = $this->createForm(CommandesType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();
            return $this->redirectToRoute('commandesPage');
        }
    
        $entityManager = $this->getDoctrine()->getManager();
        $commandes = $entityManager->getRepository(Commande::class)->findAll();


        
        return $this->render('dashboard/commandes/commandes.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
            'commandes' => $commandes,
            'modify' => $modify,
        ]);
    }
    

    #[Route('/dashboard/commandes/delete/{id}', name: 'delete_commande')]
    public function delete(Request $request, Commande $commande): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commande);
            $entityManager->flush();
            
        return $this->redirectToRoute('commandesPage');
    }

    #[Route('/dashboard/commandes/filtred', name: 'filtre_commandes')]
    public function filterAbonnements(Request $request)
    {
        $minMontant = $request->request->get('minMontant');
        $maxMontant = $request->request->get('maxMontant');
        $statut = $request->request->get('statut');

        //filtre
        $entityManager = $this->getDoctrine()->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('c')
            ->from(Commande::class, 'c');

        // Add conditions based on parameters
        if ($minMontant != null) {
            $queryBuilder->andWhere('c.montant >= :minMontant')
                ->setParameter('minMontant', $minMontant);
        }

        if ($maxMontant != null) {
            $queryBuilder->andWhere('c.montant <= :maxMontant')
                ->setParameter('maxMontant', $maxMontant);
        }

        if ($statut != null) {
            $queryBuilder->andWhere('c.statut = :statut')
                ->setParameter('statut', $statut);
        }

        // Execute the query
        $commandes = $queryBuilder->getQuery()->getResult();


        $commande = new Commande();
        $form = $this->createForm(CommandesType::class, $commande);

        return $this->render('dashboard/commandes/commandes.html.twig', [
            'commande' => null,
            'form' => $form->createView(),
            'commandes' => $commandes,
            'modify' => false,
        ]);
    }





    //add panier 


    #[Route('/dashboard/product/addTocart/{id}', name: 'addToCart')]
    public function addToCart(Request $request,SessionInterface $session, Product $product )
    {

        if($session->has('accountuser')){
            //variable globale mta session esmha panier[] 
            $cartItems = $session->get('panier', []);
             $cartItems[] = $product;//ajouti produit f e5er tableau carditems 
            $session->set('panier', $cartItems);//panier = cartitems 
            return $this->redirectToRoute('productsPage_Front');

        }
        else{
            return $this->redirectToRoute('app_front_signin');
        }
    }

    #[Route('/dashboard/commande/checkOut/', name: 'checkOut')]
    public function checkOut(Request $request, SessionInterface $session)
    {
        if ($session->has('accountuser')) {
            $user = $session->get('accountuser');
            $cartItems = $session->get('panier', []);
            $quantities = $request->request->get('quantities', []);
            $entityManager = $this->getDoctrine()->getManager();
            $total = 0;
            
            // Create a new Panier entity for the current user
            $panier = new Panier();
            $panier->setUser($user);
            $entityManager->persist($panier);
    
            foreach ($cartItems as $item) {
                // Get the quantity for the current item
                $quantity = $quantities[$item->getId()] ?? 0; // Use ?? operator to handle missing quantities
                
                if ($quantity > 0) {
                    // Calculate subtotal for the current item
                    $subtotal = $item->getPrice() * $quantity;
                    $total += $subtotal;
    
                    // Create a new PanierProduit entity for each item in the cart
                    $panierProduit = new Panierproduct();
                    $panierProduit->setPanier($panier);
                    $panierProduit->setProduct($item);
                    $panierProduit->setQuantite($quantity);
                    $panierProduit->setPrix($subtotal);
                    $entityManager->persist($panierProduit);
                }
            }
    
            // Create a new Commande entity for the current user and panier
            $commande = new Commande();
            $commande->setUser($user);
            $commande->setPanier($panier);
            $commande->setMontant($total);
            $commande->setStatut('Encours');
            $entityManager->persist($commande);
    
            // Flush all changes to the database
            $entityManager->flush();
    
            // Clear the cart session
            $session->set('panier', []); //panier = []
    
            return $this->redirectToRoute('productsPage_Front', ['montant' => $total, 'pay' =>true, ]);
        } else {
            return $this->redirectToRoute('app_front_signin');
        }
    }


    #[Route('/dashboard/product/paiment', name: 'paimentProduct')]
    public function paimentProduct(Request $request, SessionInterface $session)
    {
        if ($session->has('accountuser')) {
            $user = $session->get('accountuser');
            
            // Retrieve form data
            $cartname = $request->get('cartname');
            $cartcode = $request->get('cartcode');
            $montant = $request->get('montant');

    
            // Create a new Paiment entity
            $paiment = new Paiment();
            $paiment->setUser($user);
            $paiment->setCartname($cartname);
            $paiment->setCartcode($cartcode);
            $paiment->setMontant($montant);
            $paiment->setDate(new \DateTime());
    
            // Persist the Paiment entity
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($paiment);
            $entityManager->flush();
    
            // Redirect to the products page after payment
            return $this->redirectToRoute('productsPage_Front');
        } else {
            return $this->redirectToRoute('app_front_signin');
        }
    }
    
    

}
