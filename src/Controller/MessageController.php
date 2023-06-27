<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/message')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'message_index', methods:['GET'])]
    public function index(MessageRepository $messageRepository): Response
    {
          // Récupérer l'utilisateur courant (l'administrateur)

         $administrateur = $this->getUser();
         

     // Récupérer le nombre de messages envoyer par l'administrateur
     $nbMessagesEnvoyes = $messageRepository->count(['administrateur' => $administrateur]);

   // Récupérer le nombre de messages reçus par l'administrateur
   $nbMessagesRecus = $messageRepository->countMessagesRecus($administrateur);


        $messages = $messageRepository->findAll();

        return $this->render('message/index.html.twig', [
            'messages' => $messages,

            'nbMessagesEnvoyes' => $nbMessagesEnvoyes,
            'nbMessagesRecus' => $nbMessagesRecus,

        ]);
    }

    #[Route('/send', name: 'message_send', methods:['GET', 'POST'])]
    public function add(Request $request, MessageRepository $messageRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $formView =  $form->handleRequest($request);
        $administrateur = $this->getUser();



        if ($form->isSubmitted() && $form->isValid())
        {
            $parent = $form->get('parent')->getData();
            $enseignant = $form->get('enseignant')->getData();


            $message->setDateenv(new \DateTime());
            $message->setParent($parent);
            $message->setEnseignant($enseignant);
            $message->setAdministrateur($administrateur);

            $messageRepository->save($message, true);

            $this->addFlash("message","Message envoyer avec succes." );

            return $this->redirectToRoute('message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('message/send.html.twig', [
            'message' => $message,
             'form'=>$formView,
        ]);
    }

    #[Route('/received', name: 'message_received', methods: ['GET'])]
    public function receivedAction(MessageRepository $messageRepository): Response
    {

        $administrateur = $this->getUser();


        $nbMessagesNonLus = $messageRepository->count(['administrateur' => null, 'is_read' => 0]);

        $messages = $messageRepository->findBy(['administrateur' => null]);

         // Récupérer le nombre de messages reçus par l'administrateur
        $nbMessagesRecus = $messageRepository->countMessagesRecus($administrateur);
    
        return $this->render('message/received.html.twig', [
            'messages' => $messages,
            'nbMessagesRecus' => $nbMessagesRecus,
            'nbMessagesNonLus' => $nbMessagesNonLus,

        ]);
    }

    #[Route('/envoyer', name: 'message_envoyer', methods: ['GET'])]
    public function envoyer(MessageRepository $messageRepository): Response
    {

        $administrateur = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_ADMIN');


           // Récupérer le nombre de messages envoyer par l'administrateur
            $nbMessagesEnvoyes = $messageRepository->count(['administrateur' => $administrateur]);
        
        $messages = $messageRepository->createQueryBuilder('m')
        ->where('m.administrateur IS NOT NULL')
        ->getQuery()
        ->getResult();

        return $this->render('message/envoyer.html.twig', [
            'messages' => $messages,
            'nbMessagesEnvoyes' => $nbMessagesEnvoyes,

        ]);
    }

        
    #[Route('/read{id}', name: 'read', methods: ['GET'])]
    public function read(Message $message, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $message->setIsRead(true);
        
        $em->persist($message);
        $em->flush();
        
        return $this->render('message/read.html.twig', [
            'message' =>$message
        ]);
    }


    
    #[Route("/supprimer/{id}", name:"message_supprimer", methods:["POST", "GET"])]
    public function supprimer(Message $message, EntityManagerInterface $entityManager): Response
    {
        // On supprime Message
        $entityManager->remove($message);
        $entityManager->flush();

        $this->addFlash('success', 'Message supprimée avec succès !');
        // On redirige
        return $this->redirectToRoute('message_index');
    }    


}
