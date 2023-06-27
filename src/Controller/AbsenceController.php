<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Form\AbsenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AbsenceRepository;

#[Route('/absence')]
class AbsenceController extends AbstractController
{
    #[Route('/', name: 'absence_index', methods:['GET'])]
    public function index(AbsenceRepository $absenceRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $absences = $absenceRepository->findAll();

        return $this->render('absence/index.html.twig', [
            'absences' => $absences,
        ]);
    }

   /**
    * @Route("/absence/{id}", name="absence_show")
    */
    public function show(Absence $absence): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('absence/show.html.twig', [
            'absence' => $absence,
        ]);
    }


    #[Route('/add', name: 'absence_add', methods:['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // On crée une nouvelle Absence
        $absence  = new Absence();

        // On crée le formulaire
        $absenceForm = $this->createForm(AbsenceType::class, $absence);
        // on traite la requê   te du formulaire
        $absenceForm->handleRequest($request);
        // on vér   ifie si le formulaire est envoyer
        if ($absenceForm->isSubmitted() && $absenceForm->isValid()) {
            //on stocke
            $entityManager->persist($absence);
            $entityManager->flush();

            $this->addFlash('success', 'Absence ajoutée avec succès !');
            // on rédirige
            return $this->redirectToRoute('absence_index');
        }

        return $this->render('absence/add.html.twig', [
            'absenceForm' => $absenceForm->createView()
        ]);
    }



         #[Route('/edit/{id}', name: 'absence_edit', methods:['GET', 'POST'])]
        public function edit(Request $request, EntityManagerInterface $entityManager, Absence $absence): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // On crée le formulaire
        $absenceForm = $this->createForm(AbsenceType::class, $absence);
         // On traite la requête du formulaire
         $absenceForm->handleRequest($request);

        // On vérifie si le formulaire est envoyé et valide
            if ($absenceForm->isSubmitted() && $absenceForm->isValid()) {
        // On stocke les modifications
            $entityManager->flush();

            $this->addFlash('success', 'Absence modifiée avec succès !');
        // On redirige
             return $this->redirectToRoute('absence_index');
        }

        // On affiche le formulaire
            return $this->render('absence/edit.html.twig', [
           'absenceForm' => $absenceForm->createView(),
            'absence' => $absence
         ]);
    }

         
      #[Route("/supprimer/{id}", name:"absence_supprimer", methods:["POST", "GET"])]
        public function supprimer(Request $request, EntityManagerInterface $entityManager, Absence $absence): Response
    {
            // On supprime l'absence
            $entityManager->remove($absence);
            $entityManager->flush();

            $this->addFlash('success', 'Absence supprimée avec succès !');
            // On redirige
            return $this->redirectToRoute('absence_index');
    }

}   