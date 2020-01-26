<?php
namespace App\Service;

use App\Entity\LigneCommande;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Commande;
use \DateTime;
// Service pour manipuler le panier et le stocker en session
class PanierService
{
    ////////////////////////////////////////////////////////////////////////////
    const PANIER_SESSION = 'panier'; // Le nom de la variable de session du panier
    private $session; // Le service Session
    private $boutique; // Le service Boutique
    private $produitRepository;
    private $panier; // Tableau associatif idProduit => quantite
    // donc $this->panier[$i] = quantite du produit dont l'id = $i
    // constructeur du service
    public function __construct(SessionInterface $session, BoutiqueService $boutique, ProduitRepository $produitRepository)
    {
        // Récupération des services session et BoutiqueService
        $this->boutique = $boutique;
        $this->produitRepository = $produitRepository;
        $this->session = $session;
        // Récupération du panier en session s'il existe, init. à vide sinon
        $this->panier = $session->get(self::PANIER_SESSION, array());
    }
    // getContenu renvoie le contenu du panier
    // tableau d'éléments [ "produit" => un produit, "quantite" => quantite ]
    public function getContenu()
    {
        $contenu = [];

        foreach ($this->panier as $item) {
            $produit = $this->produitRepository->findOneBy(['id'=>key($item)]);
            array_push($contenu,["produit"=>$produit,"quantite"=>$item[key($item)]]);
        }


        return $contenu;
    }

    // getTotal renvoie le montant total du panier
    public function getTotal()
    {
        $totalPrice =0;
        $panier = $this->getContenu();
        foreach ($panier as $item) {
           $totalPrice +=  $item['produit']->getPrix() * $item['quantite'];
        }
        return $totalPrice;
    }

    // getNbProduits renvoie le nombre de produits dans le panier
    public function getNbProduits()
    {
        $nbProduits =0;
        foreach ($this->panier as $item) {
            $nbProduits += $item[array_key_first($item)];
        }
        return $nbProduits;
    }

    // ajouterProduit ajoute au panier le produit $idProduit en quantite $quantite
    public function ajouterProduit(int $idProduit, int $quantite = 1)
    {
        if (!$idProduit) return;
        $isFound=false;
        for ($i = 0 ; $i<sizeof($this->panier);$i++){
            if (isset($this->panier[$i][$idProduit])){
                $this->panier[$i][$idProduit]= $this->panier[$i][$idProduit] + $quantite;
                $isFound=true;
            }
        }
        if (!$isFound){
            array_push($this->panier,[$idProduit=>$quantite]);
        }
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }

    // enleverProduit enlève du panier le produit $idProduit en quantite $quantite
    public function enleverProduit(int $idProduit, int $quantite = 1)
    {
        if (!$idProduit) return;
        for ($i=0; $i<sizeof($this->panier); $i++){
            if (key($this->panier[$i])==$idProduit){
                $this->panier[$i][$idProduit] = $this->panier[$i][$idProduit] - $quantite;
                if ($this->panier[$i][$idProduit] <=0){
                    $this->supprimerProduit($idProduit);
                }
            }
        }
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }

    // supprimerProduit supprime complètement le produit $idProduit du panier
    public function supprimerProduit(int $idProduit)
    {
        if (!$idProduit) return;

        for ($i=0; $i<sizeof($this->panier); $i++){
            if (key($this->panier[$i])==$idProduit){
                array_splice($this->panier, $i, 1);
                }
            }
            $this->session->set(self::PANIER_SESSION, $this->panier);

        }


    // vider vide complètement le panier
    public function vider()
    {
        $this->session->set(self::PANIER_SESSION, array());
    }

    public function panierToCommande($user,$panier, $entityManager){
        if (sizeof($panier)==0) return;

        $commande = new Commande();
        $commande->setIdUsager($user->getId());
        $now = new DateTime();
        $commande->setDateCommande($now->format('Y-m-d H:i:s'));
        $commande->setStatut("enAttente");

        $entityManager->persist($commande);

         foreach($panier as $row){
             $ligneCommande = new LigneCommande();
             $ligneCommande-> setIdArticle($row['produit']);
             $ligneCommande-> setIdCommande($commande);
             $ligneCommande-> setPrix($row['produit']->getPrix());
             $ligneCommande-> setQuantite($row['quantite']);
             $entityManager->persist($ligneCommande);
        }
        $entityManager->flush();

        $this->vider();
         return $commande;
    }

}
