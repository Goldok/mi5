<?php
namespace App\Controller;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompteController extends AbstractController {
    public function index(CommandeRepository $commandeRepository) {

        $user = $this->getUser();
        $nbCommandes = sizeof($commandeRepository->findBy(['id_usager'=>$user]));
        return $this->render('compte.html.twig', ["user"=>$user, "nbCommandes"=>$nbCommandes
        ]);

    }
}

?>