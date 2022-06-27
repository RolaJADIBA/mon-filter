<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Form\AnnonceContactType;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

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

        //modifier l'annonce 
        // $form = $this->createForm(AnnoncesType::class, $annonce);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {

        //     $doctrine = $this->getDoctrine()->getManager();
        //     $doctrine->persist($annonce);
        //     $doctrine->flush();
    
        //     return $this->redirectToRoute('annonce_user', [], Response::HTTP_SEE_OTHER);
        // }


        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
            // 'form' => $form->createView()
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
    public function annonceSupprimer($id, AnnoncesRepository $annoncesRepository)
    {
        // $image = $annonce->getImages();
        // $nomImage = $this->getParameter('image_annonce'). '/' . $image->getName();
        // if(file_exists($nomImage)){
        //     unlink($nomImage);
        // }

        $annonce = $annoncesRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();

        $this->addFlash('message', 'Annonce supprimée avec succès');

        return $this->redirectToRoute('home');

    }

    /**
     * @Route("/stats" , name="stats")
     */
    public function stastique(CategoriesRepository $categoriesRepository, AnnoncesRepository $annoncesRepository)
    {
        $categories = $categoriesRepository->findAll();

        $catNom = [];
        $catColor = [];
        $catCount = [];

        foreach($categories as $categorie){
            $catNom[] = $categorie->getNom();
            $catColor[] = $categorie->getColor();
            $catCount[] = count($categorie->getAnnonces());
        }

        $annonces = $annoncesRepository->countByDate();
        $dates = [];
        $annnoncesCount = [];

        // dd($annonces);
        foreach($annonces as $annonce)
        {
            $dates[] = $annonce['dateAnnonces'];
            $annnoncesCount[] = $annonce['count'];
        }

        
        return $this->render('annonce/stats.html.twig',[
            'catNom' => json_encode($catNom),
            'catColor' => json_encode($catColor),
            'catCount' => json_encode($catCount),
            'dates' => json_encode($dates),
            'annnoncesCount' => json_encode($annnoncesCount)
        ]);
    }

        /**
     * @Route("/annonce/details/{id}" , name="annonce_details")
     */
    public function annonceDetails(AnnoncesRepository $annoncesRepository, $id, Request $request, MailerInterface $mailer)
    {
        $annonce = $annoncesRepository->find($id);
        $user = $annonce->getUser();
        $annoncesUser = $annoncesRepository->findBy(['user' => $user]);

        $form_contact = $this->createForm(AnnonceContactType::class);
        $contact = $form_contact->handleRequest($request);

        if ($form_contact->isSubmitted() && $form_contact->isValid()) {
            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to($user->getEmail())
                ->subject('Contact au sujet de votre subject"' . $annonce->getTitre() . '"')
                ->htmlTemplate('email/contact.html.twig')
                ->context([
                    'annonce' => $annonce,
                    'mail' => $contact->get('email')->getData(),
                    'message' => $contact->get('message')->getData()
                ]);
                $mailer->send($email);
                $this->addFlash('message', 'votre email a bien été envoyé');
                return $this->redirectToRoute('annonce_details', ['id' => $annonce->getId()]);
        }
        
        return $this->render('annonce/details.html.twig',[
            'annonce' => $annonce,
            'annoncesUser' => $annoncesUser,
            'form_contact' => $form_contact->createView()
        ]);
    }

}
