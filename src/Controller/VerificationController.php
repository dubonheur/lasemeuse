<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VerificationController extends AbstractController
{
    #[Route('/verifier_nouveaux_enregistrements', name: 'verifier_nouveaux_enregistrements', methods: ['GET', 'POST'])]
    public function verifierNouveauxEnregistrements(EntityManagerInterface $entityManager, MessageRepository $messageRepository)
    {
        $administrateur = $this->getUser();



        $nouveauxEnregistrements = $messageRepository->countMessagesRecus($administrateur);

        if ($nouveauxEnregistrements > $nouveauxEnregistrements) 
        {
            return new Response('nouveaux_enregistrements');
        }

        return new Response('');
    }
}