<?php

namespace App\Controller;

use App\Entity\Intern;
use App\Form\InternType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InternController extends AbstractController
{
    #[Route('/intern', name: 'app_intern')]

    // Whenever we want to do sth with DB, we need to use ManagerRegistry class
    public function index(ManagerRegistry $doctrine): Response
    {   
        //This variable stocks all the categories from DB
        //findBy() -> allow us to get the data from DB
        $interns = $doctrine->getRepository(Intern::class)->findBy([], ["last_name" => "ASC"]);

        //First argument -> view path
        //Second argument -> An array to stock the data
        return $this->render('intern/index.html.twig', ['interns' => $interns]);
    }

//!ADD A NEW INTERN---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/intern/add', name: 'add_intern')]
    #[Route('/intern/{id}/edit', name: 'edit_intern')] 

    //Function performs 2 functionality -> 1) Create the form 2)Add the form through the filter                                                                                     
    // ManagerRegistry -> Whenever we want to communicate with the DB
    public function add (ManagerRegistry $doctrine, Intern $intern=null, Request $request): Response
    {   
        //If $intern = null -> we ADD
        if(!$intern)
        {   //getData() -> The function which gets th edata about the object 
            //If $intern=null -> getData(), Gets no data
            $intern = new Intern();
        }

        //define the form
        $form = $this->createForm (InternType::class, $intern);

        //define what to happen with the form
        //handleRequest()->Analyze what's going on in the query -> Allows to takes the data and put it in the form
        $form->handleRequest($request);

        //Form processing
        if ($form->isSubmitted() && $form->isValid())
        {
            //Takes data from the formType & put it in $intern variable
            $intern = $form->getData();

            //We need to initialize getManager() to have access to persist() & flush() functions
            $entityManager = $doctrine->getManager();

            //persist() = prepare() in PDO
            $entityManager->persist($intern);

            //flush() = execute() in PDO
            $entityManager->flush();

            //'app_intern' -> Name of the route
            //Allows us to redirect to the route which showes all the interns
            return $this->redirectToRoute('app_intern');
        }
        return $this->render('intern/add.html.twig', ['formAddIntern'=>$form->createView(),
            //Returns a booleen-> If getId() of $intern exists, We are in editing case
                                                    'edit'=>$intern->getId()]);
    }
   


//!DELETE AN INTERN---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/intern/{id}/delete', name: 'delete_intern')] 

    public function delete(ManagerRegistry $doctrine, Intern $intern): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($intern);
        $entityManager->flush();
        return $this->redirectToRoute('app_intern');
    }

 
//!THE FUNCTION WITH {id} -> ALWAYS AT THE END---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/intern/{id}', name: 'show_intern')]
    //! Get by id ->Here we define id in the path which allow us to get the specific id through paramConverter
    // !The method which gets the id must always be at the end
    // each time we want to call sth by 'id' -> we also need to set {id} in the path 
    public function show (Intern $intern): Response
    {   
        // We don't need to define the id -> paramConverter will detect it automatically
        // render(Path, Array in controller)
        return $this->render('intern/show.html.twig', ['intern'=>$intern]);
    }   
}
