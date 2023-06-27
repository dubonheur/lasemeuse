<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Form\EleveFormType;
use App\Repository\EleveRepository;
use App\Service\ImagesUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/eleve", name="")
 */
class EleveController extends AbstractController
{
    /**
     * @Route("/", name="eleve_index")
     */
    public function index(EleveRepository $eleveRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $eleves = $eleveRepository->findAll();

        return $this->render('eleve/index.html.twig', [
            'eleves' => $eleves,
        ]);
    }

    /**
     * @Route("/ajouter", name="ajouter_eleve")
     */
    public function ajouter(Request $request, EntityManagerInterface $entityManager, ImagesUploader $uploader): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

    
        // On crée un nouvel eleve
        $eleve = new Eleve();
    
        // On crée le formulaire
        $eleveForm = $this->createForm(EleveFormType::class, $eleve);
    
        // on traite la requête du formulaire
        $eleveForm->handleRequest($request);
    
        // on vérifie si le formulaire est envoyé et valide
        if ($eleveForm->isSubmitted() && $eleveForm->isValid()) {

              //on veut uploader les images
              $photo = $eleveForm->get('photo')->getData();
              if($photo){
                  $uploaded=$uploader->upload($photo);
                  $eleve->setPhoto($uploaded);
              }
             //fin upload
    
            // on persiste l'objet Eleve
            $entityManager->persist($eleve);
            $entityManager->flush();
    
            $this->addFlash('success', 'Eleve ajouté avec succès');
            return $this->redirectToRoute('eleve_index');
        }
    
        return $this->render('eleve/add.html.twig', [
            'eleveForm' => $eleveForm->createView(),
        ]);
    }
    /**
    * @Route("/eleve/{id}", name="eleve_show")
    */
    public function show(Eleve $eleve): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

         return $this->render('eleve/show.html.twig', [
        'eleve' => $eleve,
        ]);
    }

    /**
     * Modifier un utilisateur
     * @route("/eleve/modifier/{id}", name="eleve_modifier")
     */
    public function edit(Eleve $eleve, Request $request,EntityManagerInterface $entityManager,ImagesUploader $uploader)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');


        $form = $this->createForm(EleveFormType::class, $eleve );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

                 //on veut uploader les images
                 $photo = $form->get('photo')->getData();
                 if($photo){
                     $uploaded=$uploader->upload($photo);
                     $eleve->setPhoto($uploaded);
                 }
                //fin upload
            
            $entityManager->persist($eleve);
            $entityManager->flush();

            $this->addFlash('message', 'utilisateur modifié avec succès');
            return $this->redirectToRoute('eleve_index');

        }

        return $this->render('eleve/edit.html.twig',[
            'eleveForm' =>$form->createView()
        ]);
    }
   

   /**
    * @Route("/eleve/supprimer/{id}", name="eleve_supprimer")
    */
    public function supprimer(Eleve $eleve, EntityManagerInterface $entityManager): Response
    {
                // On supprime l'élève
                $entityManager->remove($eleve);
                $entityManager->flush();
    
                $this->addFlash('success', 'Elève supprimée avec succès !');
                // On redirige
                return $this->redirectToRoute('eleve_index');
    }


}