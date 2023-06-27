<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Form\InscriptionType;
use App\Repository\InscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/inscription')]
class InscriptionController extends AbstractController
{
    #[Route('/', name: 'inscription_index', methods:['GET'])]
    public function index(InscriptionRepository $inscriptionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $inscriptions = $inscriptionRepository->findAll();

        return $this->render('inscription/index.html.twig', [
            'inscriptions' => $inscriptions,
        ]);
    }

   /**
    * @Route("/inscription/{id}", name="inscription_show")
    */
    public function show(Inscription $inscription): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('inscription/show.html.twig', [
            'inscription' => $inscription,
        ]);
    }

    #[Route('/add', name: 'inscription_add', methods:['GET', 'POST'])]
    public function add(Request $request,InscriptionRepository $inscriptionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $inscription = new Inscription();
        $form = $this->createForm(InscriptionType::class, $inscription);
       $formview =  $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $inscriptionRepository->save($inscription, true);

            return $this->redirectToRoute('inscription_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inscription/add.html.twig', [
            'inscription' => $inscription,
             'form'=>$formview,
        ]);
    }

    #[Route('/{id}/edit', name: 'inscription_edite', methods:['GET', 'POST'])]
    public function edit(Request $request, Inscription $inscription, InscriptionRepository $inscriptionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');


        $form = $this->createForm(InscriptionType::class, $inscription);
        $formView = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $inscriptionRepository->save($inscription, true);

            return $this->redirectToRoute('inscription_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->render('inscription/edit.html.twig', [
            'inscription' => $inscription,
            'form' => $formView
        ]);
    }

   /**
    * @Route("/inscription/delete/{id}", name="inscription_supprimer")
    */
    public function supprimer(Inscription $inscription, EntityManagerInterface $entityManager): Response
    {
                // On supprime l'inscription
                $entityManager->remove($inscription);
                $entityManager->flush();
    
                $this->addFlash('success', 'inscription supprimée avec succès !');
                // On redirige
                return $this->redirectToRoute('inscription_index');
    }

   
}
