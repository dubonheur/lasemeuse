<?php

namespace App\Controller;

use App\Entity\Garder;
use App\Form\GarderType;
use App\Repository\GarderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/garder')]
class GarderController extends AbstractController
{
    #[Route('/', name: 'garder_index', methods:['GET'])]
    public function index(GarderRepository $garderRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $gardes = $garderRepository->findAll();

        return $this->render('garder/index.html.twig', [
            'gardes' => $gardes,
        ]);
    }

   /**
    * @Route("/garder/{id}", name="garder_show")
    */
    public function show(Garder $garder): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('garder/show.html.twig', [
            'garder' => $garder,
        ]);
    }

    #[Route('/add', name: 'garder_add', methods:['GET', 'POST'])]
    public function add(Request $request,GarderRepository $garderRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $garde = new Garder();
        $form = $this->createForm(GarderType::class, $garde);
        $formView =  $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $garderRepository->save($garde, true);

            return $this->redirectToRoute('garder_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('garder/add.html.twig', [
            'garde' => $garde,
             'form'=>$formView,
        ]);
    }

    #[Route('/edit/{id}', name: 'garder_edit', methods:['GET', 'POST'])]
    public function edit(Request $request, Garder $garde, GarderRepository $garderRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(GarderType::class, $garde);
        $formView = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $garderRepository->save($garde, true);
      
            return $this->redirectToRoute('garder_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->render('garder/edit.html.twig', [
            'garde' => $garde,
            'form' => $formView
        ]);
    }

    #[Route("/supprimer/{id}", name:"garder_supprimer", methods:["POST", "GET"])]
    public function supprimer(Garder $garde, EntityManagerInterface $entityManager): Response
    {
        // On supprime Garder
        $entityManager->remove($garde);
        $entityManager->flush();

        $this->addFlash('success', 'Garde supprimée avec succès !');
        // On redirige
        return $this->redirectToRoute('garder_index');
    }    
} 



