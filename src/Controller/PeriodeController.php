<?php

namespace App\Controller;

use App\Entity\Periode;
use App\Form\PeriodeFormType;
use App\Repository\PeriodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/periode')]
class PeriodeController extends AbstractController
{
    #[Route('/', name: 'periode_index', methods:['GET'])]
    public function index(PeriodeRepository $periodeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $periodes = $periodeRepository->findAll();

        return $this->render('periode/index.html.twig', [
            'periodes' => $periodes,
        ]);
    }

   /**
    * @Route("/periode/{id}", name="periode_show")
    */
    public function show(Periode $periode): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('periode/show.html.twig', [
            'periode' => $periode,
        ]);
    }


    #[Route('/add', name: 'periode_add', methods:['GET', 'POST'])]
    public function add(Request $request, PeriodeRepository $periodeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $periode = new Periode();
        $form = $this->createForm(PeriodeFormType::class, $periode);
        $formview =  $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $periodeRepository->save($periode, true);

            return $this->redirectToRoute('periode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('periode/add.html.twig', [
            'periode' => $periode,
             'form'=>$formview,
        ]);
    }

    #[Route('/{id}/edit', name: 'periode_edit', methods:['GET', 'POST'])]
    public function edit(Request $request, Periode $periode, PeriodeRepository $periodeRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(PeriodeFormType::class, $periode);
        $formView = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $periodeRepository->save($periode, true);

            return $this->redirectToRoute('periode_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->renderForm('periode/edit.html.twig', [
            'periode' => $periode,
            'form' => $formView
        ]);
    }

   /**
    * @Route("/periode/delete/{id}", name="periode_delete")
    */
    public function supprimer(Periode $periode, EntityManagerInterface $entityManager): Response
    {
                // On supprime periode
                $entityManager->remove($periode);
                $entityManager->flush();
    
                $this->addFlash('success', 'Periode supprimée avec succès !');
                // On redirige
                return $this->redirectToRoute('periode_index');
    }

}
