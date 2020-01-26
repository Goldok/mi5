<?php
namespace App\Controller;
use App\Repository\CommandeRepository;

use App\Repository\LigneCommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController {
    public function index(CommandeRepository $commandeRepository, LigneCommandeRepository $ligneCommandeRepository) {

        $user  = $this->getUser();
    //    var_dump($user);
        $result = [];
        $commandes = $commandeRepository->findBy(['id_usager' => $user]); //find by
        for ($i=0;$i<sizeof($commandes); $i++){
            $lignesCommande = $ligneCommandeRepository->findBy(['id_commande' => $commandes[$i]->getId()]);
            $prixTotal=0;
            $quantiteTotal = 0;
            for ($j=0;$j<sizeof($lignesCommande); $j++){
                $prixTotal =  $prixTotal + $lignesCommande[$j]->getPrix() * $lignesCommande[$j]->getQuantite() ;
                $quantiteTotal = $quantiteTotal + $lignesCommande[$j]->getQuantite();
            }
            array_push($result,['commande' =>$commandes[$i],'quantite' =>$quantiteTotal,'prix' =>$prixTotal]);
        }
    //var_dump($result);
        return $this->render('commandes.html.twig', ["commandes" =>$result
        ]);

    }
}




?>