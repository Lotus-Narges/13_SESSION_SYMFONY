<?php

namespace App\Controller;

use App\Entity\SessionCours;
use App\Form\SessionCoursType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionCoursController extends AbstractController
{
    #[Route('/session/cours', name: 'app_session_cours')]

    // Whenever we want to dp sth with DB we need to use ManagerRegistory class
    public function index(ManagerRegistry $doctrine): Response
    {   
        //This variable stocks all the categories from DB
        //findBy() -> allow us to get the data from DB
        $sessionCourses = $doctrine->getRepository(SessionCours::class)->findAll();

        //First argument -> view path
        //Second argument -> An array to stock the data
        return $this->render('session_cours/index.html.twig', ['sessionCourses' => $sessionCourses]);
    }


//!ADD A NEW SESSIONCOURS---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/session/cours/add', name: 'add_session_cours')]
    #[Route('/session/cours/{id}/edit', name: 'edit_session_cours')]  

    //Function performs 2 functionality -> 1) Create the form 2)Add the form through the filter                                                                                     
    // ManagerRegistry -> Whenever we want to communicate with the DB
    public function add (ManagerRegistry $doctrine, SessionCours $sessionCours=null, Request $request): Response
    {   
        //If $sessionCours = null -> we ADD
        if(!$sessionCours)
        {   //getData() -> The function which gets th edata about the object 
            //If $sessionCours=null -> getData(), Gets no data
            $sessionCours = new SessionCours();
        }
        
        //define the form
        $form = $this->createForm (SessionCoursType::class, $sessionCours);

        //define what to happen with the form
        //handleRequest()->Analyze what's going on in the query -> Allows to takes the data and put it in the form
        $form->handleRequest($request);

        //Form processing
        if ($form->isSubmitted() && $form->isValid())
        {
            //Takes data from the formType & put it in $intern variable
            $sessionCours = $form->getData();

            //We need to initialize getManager() to have access to persist() & flush() functions
            $entityManager = $doctrine->getManager();

            //persist() = prepare() in PDO
            $entityManager->persist($sessionCours);

            //flush() = execute() in PDO
            $entityManager->flush();

            //'app_intern' -> Name of the route
            //Allows us to redirect to the route which showes all the interns
            return $this->redirectToRoute('app_session_cours');
        }
        return $this->render('session_cours/add.html.twig', ['formAddSessionCours'=>$form->createView(),
                                                                'edit'=>$sessionCours->getId()]);
    }


//!DELETE A SESSIONCOURS---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/session/cours/{id}/delete', name: 'delete_session_cours')] 

    public function delete(ManagerRegistry $doctrine, SessionCours $sessionCours): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($sessionCours);
        $entityManager->flush();
        return $this->redirectToRoute('app_session_cours');
    }



//!THE FUNCTION WITH {id} -> ALWAYS AT THE END---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/session/cours/{id}', name: 'show_session_cours')]
    // Get by id ->Here we define id in the path which allow us to get the specific id through paramConverter
    // The method which gets the id must always be at the end
    // each time we want to call sth by 'id' -> we also need to set {id} in the path 
    public function show (SessionCours $sessionCours): Response
    {   
        // We don't need to define the id -> paramConverter will detect it automatically
        // render(Path, Array in controller)
        return $this->render('session_cours/show.html.twig', ['sessionCours'=>$sessionCours]);
    }
}
