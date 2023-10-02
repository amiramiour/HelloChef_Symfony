<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    #[Route('/recettes', name: 'app_recettes')]
    public function Affichrecettes(RecetteRepository $recette): Response
    {
        $recettes=$recette->findAll();
        return $this->render('default/recettes.html.twig', [
            'recettes' => $recettes,
        ]);
    }
   #[Route('/', name: 'app_homepage')]
    public function random(RecetteRepository $recette): Response
    {
        // Fetch all the data from the repository
        $data = $recette->findAll();

        // Randomly select three or four rows from the data
        /*$numRows = count($data);
        $numSelectedRows = min(4, $numRows);
        $selectedRows = array_rand($data, $numSelectedRows);*/
        $numRows = count($data);
        $numSelectedRows = min(4, $numRows);
        $selectedKeys = array_rand($data, $numSelectedRows);
        $selectedRows = is_array($selectedKeys) ? $selectedKeys : [$selectedKeys];
        // Create an array with only the selected rows
       /* $selectedData = [];
        foreach ($selectedRows as $index) {
            $selectedData[] = $data[$index];
        }*/
        $selectedData = [];
        foreach ($selectedRows as $key) {
            $selectedData[] = $data[$key];
        }

        // Pass the selected rows to the view as a variable
        return $this->render('default/index.html.twig', [
            'selectedData' => $selectedData,
        ]);
    }
    #[Route('/recette/{slug}', name: 'app_recette')]
    public function getRecette(RecetteRepository $recetteRepo, $slug = ''): Response
    {
        $recette = $recetteRepo->findOneBy(['slug' => $slug]);

        // Assuming you have an 'id' property in the 'Categorie' entity
        $categorieId = $recette->getCategorie()->getId();

        $otherRecettes = $recetteRepo->findBy(['categorie' => $categorieId]);

        return $this->render('default/rec.html.twig', [
            'recette' => $recette,
            'otherRecettes' => $otherRecettes
        ]);
    }
    #[Route('/categories', name: 'app_categories')]
    public function categories(CategorieRepository $categorieRep): Response
    {
        $categories=$categorieRep->findAll();
        $recettesParCategorie = [];

        foreach ($categories as $categorie) {
            // Récupérer les recettes associées à la catégorie en utilisant une méthode de votre choix
            $recettes = $categorie->getRecettes(); // Supposons qu'il y ait une relation entre Categorie et Recette dans vos entités
            $recettesParCategorie[$categorie->getId()] = $recettes;
        }
        return $this->render('default/categories.html.twig', [
            'categorie' => $categories,
            'recettesParCategorie' => $recettesParCategorie,
        ]);
    }
   /* #[Route('/categories/{id}', name: 'app_recettes_par_categorie')]
    public function recettesParCategorie(CategorieRepository $categorieRepo, $id): Response
    {
        $categorie = $categorieRepo->find($id);
        $recettes = $categorie->getRecettes();

        return $this->render('default/recettes_par_categorie.html.twig', [
            'categorie' => $categorie,
            'recettes' => $recettes,
        ]);
    }*/
}
