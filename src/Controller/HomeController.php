<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\AnnonceType;
use App\Entity\Categories;
use App\Entity\CategoriesDetails;
use App\Entity\Details;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, AnnoncesRepository $annoncesRepository): Response
    {
        $annonces = $this->getDoctrine()->getRepository(Annonces::class)->findAll();

        $annonceTypes = $this->getDoctrine()->getRepository(AnnonceType::class)->findAll();

        $categories = $this->getDoctrine()->getRepository(Categories::class)->findAll();

        $categorieDetails = $this->getDoctrine()->getRepository(CategoriesDetails::class)->findAll();

        $details = $this->getDoctrine()->getRepository(Details::class)->findAll();

        $annoncesInterval = $annoncesRepository->setInterval("2000-01-01", "2008-01-01", 24);
        // dd($annoncesInterval);
        $annonce = new Annonces();
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //ajouter date d'inscription à la meme date d'hurjourdhui
            $date = new DateTime();
            $annonce->setCreatedAt($date);

            //ajouter l'user qui est connecter 
            $user = $this->getUser();
            //ajouter une image à l'annonce
            $image = $form->get('images')->getData();
            $nouveau_nom = md5(uniqid()).'.'.$image->guessExtension();
            $image->move(
                $this->getParameter('annonces'),
                $nouveau_nom
            );
            $annonce->setImages($nouveau_nom);

            $annonce->setUser($user);

            $annoncesRepository->add($annonce);
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('home/index.html.twig', [
            'annonces' => $annonces,
            'categories' => $categories,
            'annonceTypes' => $annonceTypes,
            'categorieDetails' => $categorieDetails,
            'details' => $details,
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**************************************SEARCH*********************************************** */
    /**
     * @Route("/search", name="search")
     */
    public function search(AnnoncesRepository $annoncesRepository, Request $request)
    {
        $search = $request->query->get('query');

            $annonces = $annoncesRepository->search($search);

        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }

}
