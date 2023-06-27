<?php

namespace App\Controller;

use App\Entity\Annee;
use App\Form\AnneeFormType;
use App\Repository\AnneeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/annee')]
class AnneeController extends AbstractController
{
    #[Route('/', name: 'annee_index', methods:['GET'])]
    public function index(AnneeRepository $anneeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $annees = $anneeRepository->findAll();

        return $this->render('annee/index.html.twig', [
            'annees' => $annees,
        ]);
    }

   /**
    * @Route("/annee/{id}", name="annee_show")
    */
    public function show(Annee $annee): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('annee/show.html.twig', [
            'annee' => $annee,
        ]);
    }
    
    #[Route('/add', name: 'annee_add', methods:['GET', 'POST'])]
    public function add(Request $request,AnneeRepository $anneeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $annee = new Annee();
        $form = $this->createForm(AnneeFormType::class, $annee);
        $formView =  $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $anneeRepository->save($annee, true);

            return $this->redirectToRoute('annee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annee/add.html.twig', [
            'annee' => $annee,
             'form'=>$formView,
        ]);
    }

    #[Route('/edit/{id}', name: 'annee_edit', methods:['GET', 'POST'])]
    public function edit(Request $request, Annee $annee, AnneeRepository $anneeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');


        $form = $this->createForm(AnneeFormType::class, $annee);
        $formView = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $anneeRepository->save($annee, true);
      
            return $this->redirectToRoute('annee_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->render('annee/edit.html.twig', [
            'annee' => $annee,
            'form' => $formView
        ]);
    }

    #[Route("/supprimer/{id}", name:"annee_supprimer", methods:["POST", "GET"])]
    public function supprimer(Annee $annee, EntityManagerInterface $entityManager): Response
    {
        // On supprime Année
        $entityManager->remove($annee);
        $entityManager->flush();

        $this->addFlash('success', 'Année supprimée avec succès !');
        // On redirige
        return $this->redirectToRoute('annee_index');
    }    
} 
