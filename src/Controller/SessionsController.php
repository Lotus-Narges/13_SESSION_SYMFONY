<?php

namespace App\Controller;

use App\Entity\Intern;
use App\Entity\Sessions;
use App\Form\SessionsType;
use App\Entity\SessionCours;
use App\Repository\SessionsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SessionsController extends AbstractController
{
    #[Route('/sessions', name: 'app_sessions')]

    // Whenever we want to dp sth with DB we need to use ManagerRegistory class
    public function index(ManagerRegistry $doctrine): Response
    {   
        //This variable stocks all the categories from DB
        //findBy() -> allow us to get the data from DB
        $sessions = $doctrine->getRepository(Sessions::class)->findBy([], ["title_session" => "ASC"]);

        //First argument -> view path
        //Second argument -> An array to stock the data
        return $this->render('sessions/index.html.twig', ['sessions' => $sessions]);
    }



//!ADD A NEW SESSION---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/sessions/add', name: 'add_sessions')]
    #[Route('/sessions/{id}/edit', name: 'edit_sessions')] 

    //Function performs 2 functionality -> 1) Create the form 2)Add the form through the filter                                                                                     
    // ManagerRegistry -> Whenever we want to communicate with the DB
    public function add (ManagerRegistry $doctrine, Sessions $session=null, Request $request): Response
    {   
        //If $session = null -> we ADD
        if(!$session)
        {   //getData() -> The function which gets th edata about the object 
            //If $session=null -> getData(), Gets no data
            $session = new Sessions();
        }
        
        //define the form
        $form = $this->createForm (SessionsType::class, $session);

        //define what to happen with the form
        //handleRequest()->Analyze what's going on in the query -> Allows to takes the data and put it in the form
        $form->handleRequest($request);

        //Form processing
        if ($form->isSubmitted() && $form->isValid())
        {
            //Takes data from the formType & put it in $intern variable
            $session = $form->getData();

            //We need to initialize getManager() to have access to persist() & flush() functions
            $entityManager = $doctrine->getManager();

            //persist() = prepare() in PDO
            $entityManager->persist($session);

            //flush() = execute() in PDO
            $entityManager->flush();

            //'app_intern' -> Name of the route
            //Allows us to redirect to the route which showes all the interns
            return $this->redirectToRoute('app_sessions');
        }
        return $this->render('sessions/add.html.twig', ['formAddSession'=>$form->createView(),
                                                        //Returns a booleen-> If getId() of $session exists, We are in editing case
                                                        'edit'=>$session->getId()]);
    }



//!DELETE A SESSIONS---------------------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/sessions/{id}/delete', name: 'delete_sessions')] 

    public function delete(ManagerRegistry $doctrine, Sessions $sessions): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($sessions);
        $entityManager->flush();
        return $this->redirectToRoute('app_sessions');
    }



//!NOT REGISTERED INTERNS---------------------------------------------------------------------------------------------------------------------------------------------------


    #[Route('/sessions_notRegistered/{id}', name: 'app_sessions_notRegistered')]
    //Show the list of not registered interns

    // Whenever we want to dp sth with DB we need to use ManagerRegistory class
    public function notRegisteredInterns (ManagerRegistry $doctrine, Intern $intern, Sessions $session): Response
    {   
        // The method we created in Repository/sessionsRepository
        // $notRegisteredIntrns = $interns->getUnregisteredInterns();
        // $interns = $doctrine->getRepository(Sessions::class)->getNotregisteredInterns('id':sessions.id);

        //The method that's automatically created in sessions Entity for removing the interns
        $session->removeIntern($intern);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('sessions/show.html.twig', ['id' => $session->getId()]);
    }



//!DELETE AN INTERN IN THE SESSION (delete from associative table between Intern & Sessions)---------------------------------------------------------------------------------------------------------------------------------------------------

    #[Route('/sessions/remove_intern/{idSe}/{idI}', name:'remove_session_intern')]
    #[ParamConverter('session', options: ['mapping' => ['idSe' => 'id']])]
    #[ParamConverter('intern', options: ['mapping' => ['idI' => 'id']])]
    public function deleteIntern (ManagerRegistry $doctrine, Sessions $session, Intern $intern) {
        //This function we get it from Sessions Entity
        $session -> removeIntern($intern);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($session);
        $entityManager->flush();
        
        return $this->redirectToRoute('show_sessions', ['id'=> $session->getId()]);
    
    }


    #[Route('/sessions/add_intern/{idSe}/{idI}', name:'add_session_intern')]
    #[ParamConverter('session', options: ['mapping' => ['idSe' => 'id']])]
    #[ParamConverter('intern', options: ['mapping' => ['idI' => 'id']])]
    public function addIntern (ManagerRegistry $doctrine, Sessions $session, Intern $intern) {
        //This function we get it from Sessions Entity
        $session -> addIntern($intern);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($session);
        $entityManager->flush();
        
        return $this->redirectToRoute('show_sessions', ['id'=> $session->getId()]);
    
    }


//!THE FUNCTION WITH {id} -> ALWAYS AT THE END---------------------------------------------------------------------------------------------------------------------------------------------------
    
    #[Route('/sessions/{id}', name: 'show_sessions')]

    //! Get by id ->Here we define id in the path which allow us to get the specific id through paramConverter
    // !The method which gets the id must always be at the end
    // each time we want to call sth by 'id' -> we also need to set {id} in the path 
    public function show (Sessions $session, SessionsRepository $query): Response
    {   // We don't need to define the id -> paramConverter will detect it automatically

        //show the interns not registered in a specific session
        $notRegisteredInterns = $query -> getNotregisteredInterns($session->getId());
       
        // render(Path, Array in controller)
        return $this->render('sessions/show.html.twig', ['session'=>$session,
                                                        'notRegisteredInterns'=>$notRegisteredInterns]);
    }
}
