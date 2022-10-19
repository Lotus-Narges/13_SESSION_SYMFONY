<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]

    // Whenever we want to dp sth with DB we need to use ManagerRegistory class
    public function index(ManagerRegistry $doctrine): Response
    {
        //This variable stocks all the categories from DB
        //findBy() -> allow us to get the data from DB
        $modules = $doctrine->getRepository(Module::class)->findBy([], ["title_module" => "ASC"]);

        //First argument -> view path
        //Second argument -> An array to stock the data
        return $this->render('module/index.html.twig', ['modules' => $modules]);
    }


//!ADD A NEW CATEGORY---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/module/add', name: 'add_module')]
    #[Route('/module/{id}/edit', name: 'edit_module')] 

    //Function performs 2 functionality -> 1) Create the form 2)Add the form through the filter                                                                                     
    // ManagerRegistry -> Whenever we want to communicate with the DB
    public function add (ManagerRegistry $doctrine, Module $module=null, Request $request): Response
    {   
        //If $module = null -> we ADD
        if(!$module)
        {   //getData() -> The function which gets th edata about the object 
            //If $module=null -> getData(), Gets no data
            $module = new Module();
        }
        
        //define the form
        $form = $this->createForm (ModuleType::class, $module);

        //define what to happen with the form
        //handleRequest()->Analyze what's going on in the query -> Allows to takes the data and put it in the form
        $form->handleRequest($request);

        //Form processing
        if ($form->isSubmitted() && $form->isValid())
        {
            //Takes data from the formType & put it in $intern variable
            $module = $form->getData();

            //We need to initialize getManager() to have access to persist() & flush() functions
            $entityManager = $doctrine->getManager();

            //persist() = prepare() in PDO
            $entityManager->persist($module);

            //flush() = execute() in PDO
            $entityManager->flush();

            //'app_intern' -> Name of the route
            //Allows us to redirect to the route which showes all the interns
            return $this->redirectToRoute('app_module');
        }
        return $this->render('module/add.html.twig', ['formAddModule'=>$form->createView(),
        //Returns a booleen-> If getId() of $module exists, We are in editing case
                                                'edit'=>$module->getId()]);
    }



//!DELETE A MODULE---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/module/{id}/delete', name: 'delete_module')] 

    public function delete(ManagerRegistry $doctrine, Module $module): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($module);
        $entityManager->flush();
        return $this->redirectToRoute('app_module');
    }
   


//!THE FUNCTION WITH {id} -> ALWAYS AT THE END---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/module/{id}', name: 'show_module')]
    //! Get by id ->Here we define id in the path which allow us to get the specific id through paramConverter
    // !The method which gets the id must always be at the end
    // each time we want to call sth by 'id' -> we also need to set {id} in the path 
    public function show (Module $module): Response
    {   
        // We don't need to define the id -> paramConverter will detect it automatically
        // render(Path, Array in controller)
        return $this->render('session_cours/show.html.twig', ['module'=>$module]);
    }
}
