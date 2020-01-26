<?php
namespace App\Controller;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController {
    public function index(PanierService $panierService ) {

        $panier = $panierService->getContenu();
       // var_dump($panier[0]['quantite']);
        $totalPrice =  $panierService->getTotal();
        $nbProduits = $panierService->getNbProduits();
        return $this->render('panier.html.twig', ["panier" =>$panier, "totalPrice"=>$totalPrice, "nbProduits" =>$nbProduits
        ]);

    }

    public function ajouter(PanierService $panierService, $idProduit, $quantite ) {
    //    var_dump($idProduit);
        $panierService->ajouterProduit($idProduit, $quantite);
        return $this->redirectToRoute('panier_index');
    }

    public function enlever(PanierService $panierService, $idProduit, $quantite ) {
        $panierService->enleverProduit($idProduit, $quantite);
        return $this->redirectToRoute('panier_index');
    }

    public function supprimer(PanierService $panierService, $idProduit) {
        $panierService->supprimerProduit($idProduit);
        return $this->redirectToRoute('panier_index');
    }
    public function vider(PanierService $panierService) {
        $panierService->vider();
        return $this->redirectToRoute('panier_index');
    }

    public function panier_validation(PanierService $panierService){
          $user  = $this->getUser();
          $panier = $panierService->getContenu();
          $entityManager = $this->getDoctrine()->getManager();
          $commande = $panierService->panierToCommande($user,$panier,$entityManager);

        return $this->redirectToRoute('commande_index');

    }
}




?>