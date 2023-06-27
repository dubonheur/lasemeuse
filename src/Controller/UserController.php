<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Service\ImagesUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class UserController extends AbstractController
{  
    #[Route('/', name: 'user_index')]
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $nbUtilisateurs = $userRepository->count([]); // Compte tous les utilisateurs dans la base de données

        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,

            'nbUtilisateurs' => $nbUtilisateurs,
        ]);
    }

        #[Route("/personne/ajouter",name:"ajouter_personne")]
        public function ajouter(Request $request, EntityManagerInterface $entityManager, ImagesUploader $uploader,  UserPasswordHasherInterface $passwordHasher): Response
        {

            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            // On crée un nouvel utilisateur
            $user = new User();

        
            // On crée le formulaire
            $userForm = $this->createForm(UserFormType::class, $user);

          
        
            // on traite la requête du formulaire
            $userForm->handleRequest($request);
        
            // on vérifie si le formulaire est envoyé et valide
            if ($userForm->isSubmitted() && $userForm->isValid()) 
            {   
                $plaintextPassword = $userForm->get('password')->getData();

                $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
                $user->setPassword($hashedPassword);

                
                //on veut uploader les images
                        $photo = $userForm->get('photo')->getData();
                        if($photo){
                            $uploaded=$uploader->upload($photo);
                            $user->setPhoto($uploaded);
                        }
        
                //fin upload
        
                // On stocke l'utilisateur en base de données
                $entityManager->persist($user);
                $entityManager->flush();
        
                $this->addFlash('success', 'Utilisateur ajouté avec succès !');
        
                // on redirige
                return $this->redirectToRoute('user_index');
            }
        
            return $this->render('user/add.html.twig', [
                'userForm' => $userForm->createView()
            ]);
        }


/**
 * Afficher un utilisateur
 * @Route("/utilisateur/{id}", name="user_show")
 */
    public function show(User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('user/show.html.twig', [
        'user' => $user
        ]);
    }


    /**
     * Modifier un utilisateur
     * @route("/utilisateur/modifier/{id}", name="user_modifier_utilisateur")
     */
    public function editUser(User $user, Request $request,EntityManagerInterface $entityManager,ImagesUploader $uploader, UserPasswordHasherInterface $passwordHasher)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $plaintextPassword = $form->get('password')->getData();

            $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setPassword($hashedPassword);


             //on veut uploader les images
             $photo = $form->get('photo')->getData();
             if($photo){
                 $uploaded=$uploader->upload($photo);
                 $user->setPhoto($uploaded);
             }
     //fin upload
            
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'utilisateur modifié avec succès');
            return $this->redirectToRoute('user_index');

        }

        return $this->render('user/edituser.html.twig',[
            'userForm' =>$form->createView()
        ]);
    }

        /**
         * @Route(" /supprimer/{id}", name="user_supprimer")
         */
        public function supprimer(User $user, EntityManagerInterface $entityManager): Response
         {

            // On supprime l'utilisateur
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'user supprimée avec succès !');
            // On redirige
            return $this->redirectToRoute('user_index');
        }


     
}