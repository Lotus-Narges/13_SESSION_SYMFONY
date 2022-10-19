<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    // Whenever we want to communicate with DB -> We need to use ManagerRegistry class
    public function index(ManagerRegistry $doctrine): Response
    {   
        //This variable stocks all the categories from DB
        //findBy() -> allow us to get the data from DB
        $categories = $doctrine->getRepository(Category::class)->findBy([], ["title_category" => "ASC"]);

        //First argument -> view path
        //Second argument -> An array to stock the data
        return $this->render('category/index.html.twig', ['categories' => $categories]);
    }


//!ADD A NEW CATEGORY---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/category/add', name: 'add_category')]
    #[Route('/category/{id}/edit', name: 'edit_category')]  

    //Function add performs 2 functionality -> 1) Create the form & Add the form through the filter 2)Editing the elements in the entity                                                                                     
    // ManagerRegistry -> Whenever we want to communicate with the DB
    public function add (ManagerRegistry $doctrine, Category $category=null, Request $request): Response
    {   
        //If $category = null -> we ADD
        if(!$category)
        {   //getData() -> The function which gets th edata about the object 
            //If $category=null -> getData(), Gets no data
            $category = new Category();
        }
        //define the form
        $form = $this->createForm (CategoryType::class, $category);

        //define what to happen with the form
        //handleRequest()->Analyze what's going on in the query -> Allows to takes the data and put it in the form
        $form->handleRequest($request);

        //Form processing
        if ($form->isSubmitted() && $form->isValid())
        {
            //Takes data from the formType & put it in $intern variable
            $category = $form->getData();

            //We need to initialize getManager() to have access to persist() & flush() functions
            $entityManager = $doctrine->getManager();

            //persist() = prepare() in PDO
            $entityManager->persist($category);

            //flush() = execute() in PDO
            $entityManager->flush();

            //'app_intern' -> Name of the route
            //Allows us to redirect to the route which showes all the interns
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/add.html.twig', ['formAddCategory'=>$form->createView(),
        //Returns a booleen-> If getId() of $category exists, We are in editing case
                                                'edit'=>$category->getId()]);
    }
//!DELETE A CATEGORY---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/category/{id}/delete', name: 'delete_category')] 

    public function delete(ManagerRegistry $doctrine, Category $category): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('app_category');
    }

}
