<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce", name="app_annonce")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $donnes = $this->getDoctrine()->getRepository(Annonces::class)->findBy([], ['created_at' => 'desc']);

        $annonces = $paginator->paginate(
            $donnes,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    // ANNONCE PAR CATEGORIE
    /**
     * @Route("/annonce/categorie/{id}", name="annonce_categorie")
     */
    public function annonceCategorie($id)
    {
        $categorie = $this->getDoctrine()->getRepository(Categories::class)->find($id);
        $annonces = $this->getDoctrine()->getRepository(Annonces::class)->findBy([
            'categorie' => $categorie,
            // 'created_at' => 'desc'
        ]);

        return $this->render('annonce/categorie.html.twig', [
            'annonces' => $annonces,
            'categorie' => $categorie
        ]);
    }

    // LES ANNONCES DEPOSER PAR LE USER
    /**
     * @Route("/annonce/user" , name="annonce_user")
     */
    public function annonceUser()
    {
        $user = $this->getUser();

        $annonces = $this->getDoctrine()->getRepository(Annonces::class)->findBy(['user' => $user]);

        return $this->render('annonce/user.html.twig', [
            'annonces' => $annonces,
        ]);

    }

    /**
     * @Route("/annonce/modifier/{id}" , name="annonce_modifier")
     */
    public function annonceModifier(Annonces $annonce, Request $request)
    {
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($annonce);
            $doctrine->flush();
    
            return $this->redirectToRoute('annonce_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/user.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/annonce/supprimer/{id}" , name="annonce_supprimer")
     */
    public function annonceSupprimer(Annonces $annonce)
    {
        // $image = $annonce->getImages();
        // $nomImage = $this->getParameter('image_annonce'). '/' . $image->getName();
        // if(file_exists($nomImage)){
        //     unlink($nomImage);
        // }

        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();

        $this->addFlash('message', 'Annonce supprimée avec succès');

        return $this->redirectToRoute('app_annonce');

    }

}
