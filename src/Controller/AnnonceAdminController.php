<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annonce/admin")
 */
class AnnonceAdminController extends AbstractController
{
    /**
     * @Route("/", name="app_annonce_admin_index", methods={"GET"})
     */
    public function index(AnnoncesRepository $annoncesRepository): Response
    {
        return $this->render('annonce_admin/index.html.twig', [
            'annonces' => $annoncesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_annonce_admin_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AnnoncesRepository $annoncesRepository): Response
    {
        $annonce = new Annonces();
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annoncesRepository->add($annonce);
            return $this->redirectToRoute('app_annonce_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce_admin/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_annonce_admin_show", methods={"GET"})
     */
    public function show(Annonces $annonce): Response
    {
        return $this->render('annonce_admin/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_annonce_admin_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Annonces $annonce, AnnoncesRepository $annoncesRepository): Response
    {
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annoncesRepository->add($annonce);
            return $this->redirectToRoute('app_annonce_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce_admin/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_annonce_admin_delete", methods={"POST"})
     */
    public function delete(Request $request, Annonces $annonce, AnnoncesRepository $annoncesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $annoncesRepository->remove($annonce);
        }

        return $this->redirectToRoute('app_annonce_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
