<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Form\RendezvousType;
use App\Repository\RendezvousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rendezvous')]
class RendezController extends AbstractController
{
    #[Route('/', name: 'rendezvous_index', methods:['GET'])]
    public function index(RendezvousRepository $rendezvousRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $rendezvous = $rendezvousRepository->findAll();

        return $this->render('rendez/index.html.twig', [
            'rendezvous' => $rendezvous,
        ]);
    }

   /**
    * @Route("/show/{id}", name="rendezvous_show")
    */
    public function show(Rendezvous $rendezvous): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('rendez/show.html.twig', [
            'rendezvous' => $rendezvous,
        ]);
    }

    #[Route('/add', name: 'rendezvous_add', methods:['GET', 'POST'])]
    public function add(Request $request,RendezvousRepository $rendezvousRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $rendez = new Rendezvous();
        $form = $this->createForm(RendezvousType::class, $rendez);
        $formView =  $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $rendezvousRepository->save($rendez, true);

            return $this->redirectToRoute('rendezvous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rendez/add.html.twig', [
            'rendez' => $rendez,
             'form'=>$formView,
        ]);
    }

    #[Route('/edit/{id}', name: 'rendezvous_edit', methods:['GET', 'POST'])]
    public function edit(Request $request, Rendezvous $rendez, RendezvousRepository $rendezvousRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(RendezvousType::class, $rendez);
        $formView = $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid())
        {
            $rendezvousRepository->save($rendez, true);
      
            return $this->redirectToRoute('rendezvous_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->render('rendez/edit.html.twig', [
            'rendez' => $rendez,
            'form' => $formView
        ]);
    }

    #[Route("/supprimer/{id}", name:"rendezvous_supprimer", methods:["POST", "GET"])]
    public function supprimer(Rendezvous $rendez, EntityManagerInterface $entityManager): Response
    {
        // On supprime rendezvous
        $entityManager->remove($rendez);
        $entityManager->flush();

        $this->addFlash('success', 'Rendez-vous supprimée avec succès !');
        // On redirige
        return $this->redirectToRoute('rendezvous_index');
    }    

}
