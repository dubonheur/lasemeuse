<?php

namespace App\Controller;

use App\Entity\Emploi;
use App\Form\EmploieType;
use App\Repository\EmploiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/emploie')]
class EmploieController extends AbstractController
{
    #[Route('/', name: 'emploie_index', methods:['GET'])]
    public function index(EmploiRepository $emploiRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $emploies = $emploiRepository->findAll();
        return $this->render('emploie/index.html.twig', [
            'emploies' => $emploies
        ]);
    }

   /**
    * @Route("/emploiie/{id}", name="emploie_show")
    */
    public function show(Emploi $emploie): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('emploie/show.html.twig', [
            'emploie' => $emploie,
        ]);
    }


    #[Route('/add', name: 'emploie_add', methods:['GET', 'POST'])]
    public function add(Request $request,EmploiRepository $emploiRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $emploie = new Emploi();
        $form = $this->createForm(EmploieType::class, $emploie);
        $formview = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $emploiRepository->save($emploie, true);
            return $this->redirectToRoute('emploie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emploie/add.html.twig', [
            'emploie' => $emploie,
            'form' => $formview,
        ]);
    }

    #[Route('/{id}/edit', name: 'emploie_edit', methods:['GET', 'POST'])]
    public function edit(Request $request, Emploi $emploie, EmploiRepository $emploiRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');


        $form = $this->createForm(EmploieType::class, $emploie);
        $formView = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $emploiRepository->save($emploie, true);

            return $this->redirectToRoute('emploie_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->render('emploie/edit.html.twig', [
            'emploie' => $emploie,
            'form' => $formView
        ]);
    }

    #[Route("/supprimer/{id}", name:"emploie_supprimer", methods:["POST", "GET"])]
    public function supprimer(Emploi $emploie, EntityManagerInterface $entityManager): Response
    {
        // On supprime Emploi
        $entityManager->remove($emploie);
        $entityManager->flush();

        $this->addFlash('success', 'Emploi supprimée avec succès !');
        // On redirige
        return $this->redirectToRoute('emploie_index');
    }    



}
