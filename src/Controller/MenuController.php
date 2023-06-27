<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileType;
use App\Repository\AbsenceRepository;
use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(  ClasseRepository $classeRepo, EleveRepository $eleveRepo, UserRepository $userRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $nbrParent = $userRepo->countParents();
        $nbrEnseignant = $userRepo->countEnseignants();




        return $this->render('menu/base.html.twig', [
            'nbrClasse' => sizeof($classeRepo->findAll()),
            'nbrEleve' =>  sizeof($eleveRepo->findAll()),
            'nbrParent' => $nbrParent,
            'nbrEnseignant' =>  $nbrEnseignant,


        ]);
    }


  

  

}
