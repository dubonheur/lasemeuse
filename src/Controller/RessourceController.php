<?php

namespace App\Controller;

use App\Entity\Ressourcepedagogique;
use App\Form\RessourceType;
use App\Repository\RessourcepedagogiqueRepository;
use App\Service\ImagesUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ressource')]
class RessourceController extends AbstractController
{
    #[Route('/', name: 'ressource_index', methods:['GET'])]
    public function index(RessourcepedagogiqueRepository $ressourcepedagogiqueRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $ressources = $ressourcepedagogiqueRepository->findAll();

        return $this->render('ressource/index.html.twig', [
            'ressources' => $ressources,
        ]);
    }

    #[Route('/add', name: 'ressource_add', methods:['GET', 'POST'])]
    public function add(Request $request, RessourcepedagogiqueRepository $ressourcepedagogiqueRepository, ImagesUploader $uploader): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $ressource = new Ressourcepedagogique();
        $form = $this->createForm(RessourceType::class, $ressource);
        $formView =  $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
             //on veut uploader les images
             $fichier = $form->get('fichier')->getData();
             if($fichier){
                 $uploaded=$uploader->upload($fichier);
                 $ressource->setFichier($uploaded);
             }
     
            $ressourcepedagogiqueRepository->save($ressource, true);

            return $this->redirectToRoute('ressource_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ressource/add.html.twig', [
            'ressource' => $ressource,
             'form'=>$formView,
        ]);
    }

        /**
        * Afficher un ressource
        * @Route("/show/{id}", name="ressource_show")
         */
             public function show(Ressourcepedagogique $ressource): Response
            {
                $this->denyAccessUnlessGranted('ROLE_ADMIN');

                 return $this->render('ressource/show.html.twig', [
                'ressource' => $ressource
             ]);
            }

    #[Route('/edit/{id}', name: 'ressource_edit', methods:['GET', 'POST'])]
    public function edit(Request $request, Ressourcepedagogique $ressource, RessourcepedagogiqueRepository $ressourcepedagogiqueRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(RessourceType::class, $ressource);
        $formView = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $ressourcepedagogiqueRepository->save($ressource, true);
      
            return $this->redirectToRoute('ressource_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->render('ressource/edit.html.twig', [
            'ressource' => $ressource,
            'form' => $formView
        ]);
    }

    #[Route("/supprimer/{id}", name:"ressource_supprimer", methods:["POST", "GET"])]
    public function supprimer(Ressourcepedagogique $ressource, EntityManagerInterface $entityManager): Response
    {
        // On supprime ressource
        $entityManager->remove($ressource);
        $entityManager->flush();

        $this->addFlash('success', 'ressource pédagogique  supprimée avec succès !');
        // On redirige
        return $this->redirectToRoute('ressource_index');
    }    
}
