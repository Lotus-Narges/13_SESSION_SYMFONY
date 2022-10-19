<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    #[Route('/formation', name: 'app_formation')]

    // Whenever we want to communicate with DB -> We need to use ManagerRegistry class
    public function index(ManagerRegistry $doctrine): Response
    {   
        //This variable stocks all the categories from DB
        //findBy() -> allow us to get the data from DB
        $formations = $doctrine->getRepository(Formation::class)->findBy([], ["title_formation" => "ASC"]);

        //First argument -> view path
        //Second argument -> An array to stock the data
        return $this->render('formation/index.html.twig', ['formations' => $formations]);
    }


    
//!ADD A NEW FORMATION---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/formation/add', name: 'add_formation')]
    #[Route('/formation/{id}/edit', name: 'edit_formation')]  

    //Function performs 2 functionality -> 1) Create the form 2)Add the form through the filter                                                                                     
    // ManagerRegistry -> Whenever we want to communicate with the DB
    public function add (ManagerRegistry $doctrine, Formation $formation=null, Request $request): Response
    {   
        //If $formation = null -> we ADD
        if(!$formation)
        {   //getData() -> The function which gets th edata about the object 
            //If $formation=null -> getData(), Gets no data
            $formation = new Formation();
        }
        
        //define the form
        $form = $this->createForm (FormationType::class, $formation);

        //define what to happen with the form
        //handleRequest()->Analyze what's going on in the query -> Allows to takes the data and put it in the form
        $form->handleRequest($request);

        //Form processing
        if ($form->isSubmitted() && $form->isValid())
        {
            //Takes data from the formType & put it in $intern variable
            $formation = $form->getData();

            //We need to initialize getManager() to have access to persist() & flush() functions
            $entityManager = $doctrine->getManager();

            //persist() = prepare() in PDO
            $entityManager->persist($formation);

            //flush() = execute() in PDO
            $entityManager->flush();

            //'app_intern' -> Name of the route
            //Allows us to redirect to the route which showes all the interns
            return $this->redirectToRoute('app_formation');
        }
        return $this->render('formation/add.html.twig', ['formAddFormation'=>$form->createView(),
        //Returns a booleen-> If getId() of $formation exists, We are in editing case
                                                        'edit'=>$formation->getId()]);
    }


//!DELETE A FORMATION---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/formation/{id}/delete', name: 'delete_formation')] 

    public function delete(ManagerRegistry $doctrine, Formation $formation): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($formation);
        $entityManager->flush();
        return $this->redirectToRoute('app_formation');
    }


}
