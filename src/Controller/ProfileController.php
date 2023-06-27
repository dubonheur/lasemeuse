<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\ImagesUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }


    #[Route('/{id}/edit', name: 'profile-edit', methods: ['GET', 'POST'])]
    public function updateProfile(Request $request, EntityManagerInterface $entityManager,ImagesUploader $uploader)
    {
        $user = $this->getUser(); // Récupérer l'utilisateur actuel

        // Vérifier si le formulaire a été soumis
        if ($request->request->get("submit")) {
            // Récupérer les données du formulaire
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $profession = $request->request->get('profession');
            $nationalite = $request->request->get('nationalite');
            $sexe = $request->request->get('sexe');
            $telephone = $request->request->get('telephone');
            $email = $request->request->get('email');
            $photo = $request->files->get('profileImage');
            
         // Mettre à jour les propriétés de l'utilisateur
             $user->setNom($nom);
              $user->setPrenom($prenom);
              $user->setProfession($profession);
              $user->setNationalite($nationalite);
            $user->setSexe($sexe);
             $user->setTelephone($telephone);
             $user->setEmail($email);
             if($photo){
                 $uploaded=$uploader->upload($photo);
                 $user->setPhoto($uploaded);
             }

            // Enregistrer les modifications dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page de profil mise à jour
            return $this->redirectToRoute('app_profile');
        }

         return $this->render('profile/index.html.twig', [

         ]);
    }


    #[Route('/password', name: 'profile-change-password')]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($request->request->get("submit")) {
            // Vérifier si les trois mots de passe sont identiques
            if ($request->request->get('newpassword') === $request->request->get('renewpassword')) {
                $currentPassword = $request->request->get('password');
                $newPassword = $request->request->get('newpassword');
    
                $user=$this->getUser();
                // Vérifier si l'ancien mot de passe correspond à celui de l'utilisateur
                if ($passwordHasher->isPasswordValid($user, $currentPassword)) {
                    // Hasher le nouveau mot de passe
                    $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
    
                    // Mettre à jour le mot de passe de l'utilisateur
                    $user->setPassword($hashedPassword);
                    $entityManager->flush();
    
                    $this->addFlash('message', 'Mots de passe mis à jour avec succès');
                    
                    return $this->redirectToRoute('profile-change-password');
                } else {
                    $this->addFlash('error', 'L\'ancien mot de passe est incorrect');
                }
            } else {
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }
    
        return $this->render('profile/index.html.twig', []);
    }
    

}
