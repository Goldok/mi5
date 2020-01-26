<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\BoutiqueService;
use Symfony\Component\HttpFoundation\Request;

class BoutiqueController extends AbstractController {

    public function index(CategorieRepository $categorieRepository) {
        $categories = $categorieRepository->findAll();
        return $this->render('boutique.html.twig', ["categories"=> $categories

        ]);
    }
    public function rayon($idCategorie,CategorieRepository $categorieRepository, BoutiqueService $boutique) {
        $categorie = $categorieRepository->find($idCategorie);
        $products = $categorie->getProduits();
        return $this->render('rayon.html.twig', ["produits" =>$products, "categorie" => $categorie,
        ]);
    }
    public function search(BoutiqueService $boutique, Request $request) {
        $input = $request->query->get('input');
        $products = $boutique->findProduitsByLibelleOrTexte($input);
        return $this->render('search.html.twig', ["products" => $products, "input" => $input
        ]);
    }
}
?>