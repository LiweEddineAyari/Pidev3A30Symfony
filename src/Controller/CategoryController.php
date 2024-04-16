<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/dashboard/category', name: 'categoryPage')]
    public function index(): Response
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $entityManager = $this->getDoctrine()->getManager();
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('dashboard/category/category.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'categories' => $categories,
            'modify' => false,
        ]);
    }


    #[Route('/dashboard/category/save/{id?}', name: 'save_category')]
    public function save(Request $request, Category $category = null): Response
    {
        if (!$category) {
            $modify=false;
            $category = new Category();
        }
        else{
            $modify=true;
        }
    
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('categoryPage');
        }
    
        $entityManager = $this->getDoctrine()->getManager();
        $categories = $entityManager->getRepository(Category::class)->findAll();


        
        return $this->render('dashboard/category/category.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'categories' => $categories,
            'modify' => $modify,
        ]);
    }
    

    #[Route('/dashboard/category/delete/{id}', name: 'delete_category')]
    public function delete(Request $request, Category $category): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
            
        return $this->redirectToRoute('categoryPage');
    }


}
