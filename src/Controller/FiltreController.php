<?php

namespace App\Controller;

use App\Repository\DetailsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FiltreController extends AbstractController
{
    /**
     * @Route("/filtre/{id}", name="app_filtre")
     */
    public function index(DetailsRepository $detailsRepository,$id): Response
    {
        $details = $detailsRepository->findBy(['categoriesDetails' => 25]);
        return $this->render('filtre/index.html.twig', [
            'details' => $details
        ]);
    }
}
