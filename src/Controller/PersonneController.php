<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Entity\PersonneSearch;
use App\Form\PersonneSearchType;
use App\Form\PersonneType;
use App\Repository\PersonneRepository;
use App\Service\Parametres;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/personne', name: 'pers.')]
#[IsGranted('ROLE_LECT')]
class PersonneController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private Parametres $parametres,
        private PersonneRepository $repository
    ) {
    }

    #[Route('/', name: 'index')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new PersonneSearch();
        $form = $this->createForm(PersonneSearchType::class, $search);
        $form->handleRequest($request);

        $personnes = $paginator->paginate(
            $this->repository->findMySearch($search),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'show')]
    public function show(Personne $personne): Response
    {
        $persAdr = 'S';
        if ($personne->getAdresse()) {
            if (\count($personne->getAdresse()->getPersonnes()) > 1) {
                $persAdr = 'P';
            }
        } else {
            $persAdr = 'N';
        }

        return $this->render('personne/show.html.twig', [
            'params' => $this->parametres,
            'personne' => $personne,
            'persAdr' => $persAdr,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'edit', methods: 'GET|POST')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Personne $personne, Request $request): Response
    {
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            // $this->addFlash('success', 'Personne modifiée avec succès');

            /* Affichage de l'adresse
            $adresse = $personne->getAdresse();

            return $this->render('adresse/show.html.twig', [
                'adresse' => $adresse
            ]);
            */

            return $this->redirectToRoute('pers.index');
        }

        return $this->render('personne/edit.html.twig', [
            'personne' => $personne,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request): Response
    {
        $personne = new Personne();

        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($personne);
            $this->em->flush();

            return $this->redirectToRoute('pers.index');
        }

        return $this->render('personne/new.html.twig', [
            'personne' => $personne,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}/del', name: 'delete', methods: 'DELETE')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Personne $personne, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personne->getId(), $request->get('_token'))) {
            // SoftDelete => inscription de la date de suppression
            $personne->setDeletedAt(new \DateTimeImmutable('now'));
            $this->em->flush();
        }

        return $this->redirectToRoute('pers.index');
    }
}
