<?php

namespace App\Controller;

use App\Entity\Usager;
use App\Form\Usager1Type;
use App\Repository\UsagerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UsagerController extends AbstractController
{

    public function index(UsagerRepository $usagerRepository): Response
    {
        return $this->render('usager/index.html.twig', [
            'usagers' => $usagerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="usager_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $usager = new Usager();
        $form = $this->createForm(Usager1Type::class, $usager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encryptage du mot de passe
            $usager->setPassword($passwordEncoder->encodePassword($usager,$usager->getPassword()));
            // Définition du rôle
            $usager->setRoles(["ROLE_CLIENT"]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usager);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('usager/new.html.twig', [
            'usager' => $usager,
            'form' => $form->createView(),
        ]);
    }
}
