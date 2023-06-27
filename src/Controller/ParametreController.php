<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;


class ParametreController extends AbstractController
{
    #[Route('/parametre', name: 'app_parametre')]
    public function index(): Response
    {
        return $this->render('parametre/index.html.twig', [
            'controller_name' => 'ParametreController',
        ]);
    }

    #[Route('/password', name: 'parametre-change-password')]
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
    
        return $this->render('parametre/index.html.twig', []);
    }

    #[Route('/supprimer', name: 'delete_account')]
    public function deleteAccount(EntityManagerInterface $entityManager,TokenStorageInterface $tokenStorage,SessionInterface $session): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!$this->getUser()) {
            throw new AccessDeniedException('Vous devez être connecté pour supprimer votre compte.');
        }

        // Supprimer le compte de l'utilisateur
        $user = $this->getUser();
        $entityManager->remove($user);
        $entityManager->flush();

       // On déconnecte l'utilisateur
       $tokenStorage->setToken(null);
       $session->invalidate();

        // Rediriger l'utilisateur vers une page de confirmation ou d'accueil
        return $this->redirectToRoute('app_acceuil');
    }
    
}
