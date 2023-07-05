<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Personne;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/adresse', name: 'adresse.')]
class AdresseController extends AbstractController
{
    public function __construct(
        private AdresseRepository $repository,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/{id<\d+>}/show', name: 'show', methods: 'GET|POST')]
    public function show(Personne $personne): Response
    {
        $adresse = $personne->getAdresse();

        return $this->render('adresse/show.html.twig', [
            'adresse' => $adresse,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'edit', methods: 'GET|POST')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Adresse $adresse, Request $request): Response
    {
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            // $this->addFlash('success', 'Adresse modifiée avec succès');

            return $this->redirectToRoute('pers.index');
        }

        return $this->renderForm('adresse/edit.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }

    #[Route('{id<\d+>}/new', name: 'new', methods: 'GET|POST')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Personne $personne, Request $request): Response
    {
        $adresse = new Adresse();
        $adresse->addPersonne($personne);

        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($adresse);
            $this->em->flush();
            // $this->addFlash('success', 'Adresse créée avec succès');

            return $this->redirectToRoute('pers.index');
        }

        return $this->render('adresse/new.html.twig', [
            'adresse' => $adresse,
            'form' => $form->createView(),
        ]);
    }
}
