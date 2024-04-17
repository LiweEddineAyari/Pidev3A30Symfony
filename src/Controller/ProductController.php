<?php

namespace App\Controller;

use App\Entity\Exercises;
use App\Entity\Favoriteexercices;
use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class ProductController extends AbstractController
{
    #[Route('/dashboard/product', name: 'productsPage')]
    public function index(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //
        }
        

        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('dashboard/product/products.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
            'product' =>null,
            'modify' => false,

        ]);
    }


    #[Route('/dashboard/product/save/{id?}', name: 'save_product')]
    public function save(Request $request, Product $product = null): Response
    {
        if (!$product) {
            $modify=false;
            $product = new Product();
        }
        else{
            $modify=true;
        }
    
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setImageurl($form->get('imageUrl')->getData()->getClientOriginalName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('productsPage');
        }
    
        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository(Product::class)->findAll();
        
        return $this->render('dashboard/product/products.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'products' => $products,
            'modify' => $modify,
        ]);
    }
    

    #[Route('/dashboard/product/delete/{id}', name: 'delete_product')]
    public function delete(Request $request, Product $product): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        return $this->redirectToRoute('productsPage');
    }











    //front 
    #[Route('/app/product', name: 'productsPage_Front')]
    public function productsPage_Front(Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository(Product::class)->findAll();

        $montant = $request->query->get('montant');
        $pay = $request->query->get('pay');
        
        if($montant && $pay){
            return $this->render('app_front/product/product.html.twig', [
                'products' => $products,
                'montant' => $montant, 
                'pay' =>true,
            ]);
        }

        return $this->render('app_front/product/product.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/app/product/{id}', name: 'product_details')]
    public function productsShowDetails(Request $request, Product $product,SessionInterface $session): Response
    {
        $productId = $product->getId();
        
        $entityManager = $this->getDoctrine()->getManager();
        $exercisesRepository = $entityManager->getRepository(Exercises::class);
        $exercises = $exercisesRepository->findBy(['product' => $product]);
    

        $favorites=[];
        $favoritesById=[];
        //favoris
        if($session->has('accountuser')){
            $user = $session->get('accountuser');
            $favoriteRepository = $entityManager->getRepository(Favoriteexercices::class);
            $favorites = $favoriteRepository->findBy(['iduser' => $user->getId()]); 
            
            $favoritesById = [];
            foreach ($favorites as $favorite) {
                $favoritesById[] = $favorite->getIdexercice();
            }
        }

        return $this->render('app_front/product/product-details.html.twig', [
            'product' => $product,
            'exercises' => $exercises,
            'favoritesById' =>  $favoritesById,
        ]);
    }

    
}
