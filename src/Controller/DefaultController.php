<?php
namespace App\Controller;

use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class DefaultController extends AbstractController {
    public function index() {
        return $this->render('index.html.twig', [
          //  'userName' => $userName,
        ]);
    }
    public function contact() {
        return $this->render('contact.html.twig', [

        ]);
    }

    public function getNbProducts(PanierService $panierService) {
        $nbProducts = $panierService->getNbProduits();
        return $this->render('nav_bar.html.twig',["nbProduits"=>$nbProducts]);
    }
}
?>