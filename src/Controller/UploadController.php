<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class UploadController extends AbstractController
{
   /**
     * @Route("/api/upload", methods={"POST", "GET"})
     */
    public function uploadAction(Request $request): JsonResponse
    {
        $uploadFile = $request->files->get('file');

        if (!$uploadFile) {
            return new JsonResponse(['error' => 'No file uploaded.'], 400);
        }

        try {
            $filename = md5(uniqid()) . '.' . $uploadFile->guessExtension();

            $uploadFile->move(
                $this->getParameter('images_directory'),
                $filename
            );

            return new JsonResponse(['filename' => $filename]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Error uploading file.'], 500);
        }
    }
}