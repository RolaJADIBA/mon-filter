<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Form\UserInfoType;
use App\Form\UserPhotoType;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAccountController extends AbstractController
{
    /**
     * @Route("/user/account", name="app_user_account")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, CategoriesRepository $categoriesRepository, AnnoncesRepository $annoncesRepository): Response
    {
        $user = $this->getUser();

        //Changer user photo
        $form_photo = $this->createForm(UserPhotoType::class, $user);

        $form_photo->handleRequest($request);

        $ancienne_photo = $user->getPhoto();

        if($form_photo->isSubmitted() && $form_photo->isValid()) {

        $photo = $form_photo->get('photo')->getData();

        if($photo){

            if($ancienne_photo != null) {

                $filesystem = new Filesystem();
                $filesystem->remove('uploads/nav/' . $ancienne_photo);
            }
            $nouveau_nom = md5(uniqid()) .'.'. $photo->guessExtension();

            $photo->move(
                $this->getParameter('photo_user'),
                $nouveau_nom
            );

            $user->setPhoto($nouveau_nom);
        }else{
            $user->setPhoto($ancienne_photo);
        }

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($user);
        $doctrine->flush();

        return $this->redirectToRoute('app_user_account', [], Response::HTTP_SEE_OTHER);
        }

        $formInfo = $this->createForm(UserInfoType::class, $user);

        $formInfo->handleRequest($request);

        if($formInfo->isSubmitted() && $formInfo->isValid()){

            $entityManager->persist($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_user_account', [], Response::HTTP_SEE_OTHER);

        }

        //STATSTIQUE LES ANNONCES PAR LES CATEGORIES
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

        foreach($annonces as $annonce)
        {
            $date = $annonce['dateAnnonces'];
            $annnoncesCount[] = $annonce['count'];
        }


        return $this->render('user_account/index.html.twig', [
            'user' => $user,
            'form_photo' => $form_photo->createView(),
            'formInfo' => $formInfo->createView(),
            'catNom' => json_encode($catNom),
            'catColor' => json_encode($catColor),
            'catCount' => json_encode($catCount),
            'dates' => json_encode($dates),
            'annnoncesCount' => json_encode($annnoncesCount)

        ]);
    }

}
