<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BackOfficeController extends AbstractController {

    public function index() {
        return $this->render('backOffice.html.twig', [
        ]);
    }
}