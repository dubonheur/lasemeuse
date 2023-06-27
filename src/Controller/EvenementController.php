<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'evenement_index', methods:['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $evenements = $evenementRepository->findAll();

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

   /**
    * @Route("/evenement/{id}", name="evenement_show")
    */
    public function show(Evenement $evenement): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/add', name: 'evenement_add', methods:['GET', 'POST'])]
    public function add(Request $request,EvenementRepository $evenementRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $formView =  $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/add.html.twig', [
            'evenement' => $evenement,
             'form'=>$formView,
        ]);
    }

    #[Route('/edit/{id}', name: 'evenement_edit', methods:['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(EvenementType::class, $evenement);
        $formView = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $evenementRepository->save($evenement, true);
      
            return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $formView
        ]);
    }

    #[Route("/supprimer/{id}", name:"evenement_supprimer", methods:["POST", "GET"])]
    public function supprimer(Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        // On supprime evenement
        $entityManager->remove($evenement);
        $entityManager->flush();

        $this->addFlash('success', 'Evenement supprimée avec succès !');
        // On redirige
        return $this->redirectToRoute('evenement_index');
    }    
}
