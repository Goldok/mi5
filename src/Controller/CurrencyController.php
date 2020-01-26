<?php
namespace App\Controller;
use App\Repository\CommandeRepository;
use App\Service\MonaieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CurrencyController extends AbstractController {
    public function change_currency(MonaieService $monaieService, $currency) {

        $monaieService->setCurrentCurrency($currency);
        return $this->redirectToRoute('index');


    }
}

?>
