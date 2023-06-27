<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Form\ClasseFormType;
use App\Repository\ClasseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;



/**
 * @Route("/classe", name="")
 */
class ClasseController extends AbstractController
{
     /**
     * @Route("/", name="classe_index")
     */
    public function index(ClasseRepository $classeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $classes = $classeRepository->findAll();

        return $this->render('classe/index.html.twig', [
            'classes' => $classes,
        ]);
    }

   /**
    * @Route("/classe/{id}", name="classe_show")
    */
    public function show(Classe $classe): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

            return $this->render('classe/show.html.twig', [
            'classe' => $classe,
        ]);
    }


    /**
     * @Route("/ajouter", name="ajouter_classe")
     */
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // On crée un nouvel classe
        $classe = new Classe();

        // On crée le formulaire
        $classeForm = $this->createForm(ClasseFormType::class, $classe);

        // on traite la requête du formulaire
        $classeForm->handleRequest($request);

        // on vérifie si le formulaire est envoyé et valide
        if ($classeForm->isSubmitted() && $classeForm->isValid()) {
            // on stocke
            $entityManager->persist($classe);
            $entityManager->flush();

            $this->addFlash('success', 'Classe ajouté avec succès !');

            // on redirige
            return $this->redirectToRoute('classe_index');
        }

        return $this->render('classe/add.html.twig', [
            'classeForm' => $classeForm->createView()
        ]);
    }


     /**
     * Modifier un classe
     * @route("/classe/modifier/{id}", name="modifier_classe")
     */
    public function editClasse(Classe $classe, Request $request,EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(ClasseFormType::class, $classe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $entityManager->persist($classe);
            $entityManager->flush();

            $this->addFlash('message', 'Classe modifié avec succès');
            return $this->redirectToRoute('classe_index');

        }

        return $this->render('classe/edit.html.twig',[
            'classeForm' =>$form->createView()
        ]);
    }

     /**
         * @Route("/classe/supprimer/{id}", name="supprimer")
         */
        public function supprimer(Classe $classe, EntityManagerInterface $entityManager): Response
    {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            // On supprime l'utilisateur
            $entityManager->remove($classe);
            $entityManager->flush();

            $this->addFlash('success', 'classe supprimée avec succès !');
            // On redirige
            return $this->redirectToRoute('classe_index');
    }




}
