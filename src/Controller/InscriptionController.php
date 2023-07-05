<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Entity\Inscription;
use App\Service\Parametres;
use App\Form\AffectationType;
use App\Form\InscriptionType;
use App\Service\InscriptionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\Registry;
use App\Repository\InscriptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Workflow\WorkflowInterface;   
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/inscription', name: 'insc.')]
#[IsGranted('ROLE_LECT')]
class InscriptionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private InscriptionRepository $inscriptionRepository,
        private Parametres $parametres,
        private Registry $registry,
        private RequestStack $requestStack,
        private WorkflowInterface $wkfInscriptionStateMachine,
    ) {
    }

    #[Route('/', name: 'index')]
    public function index(InscriptionService $inscriptionService): Response
    {
        return $this->render('inscription/index.html.twig', [
            'params' => $this->parametres,
            'inscrits' => $inscriptionService->getPaginatedInscrits(null, true, 8),
        ]);
    }

    #[Route('/{id<\d+>}/new', name: 'new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Personne $personne, Request $request): Response
    {
        $inscription = new Inscription();
        $inscription->setPelerinage($this->parametres->getPele());
        $inscription->setPersonne($personne);

        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workflow = $this->registry->get($inscription);

            $workflow->getMarking($inscription);

            $this->em->persist($inscription);
            $this->em->flush();

            // $this->addFlash('success', 'Inscription enregistrée');

            return $this->redirectToRoute('pers.index');
        }

        return $this->render('inscription/new.html.twig', [
            'inscription' => $inscription,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'edit', methods: 'GET|POST')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Inscription $inscription, Request $request): Response
    {
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            // $this->addFlash('success', 'Inscription modifiée avec succès');

            return $this->redirect($this->generateUrl('insc.show', ['id' => $inscription->getId()]));
        }

        return $this->render('inscription/edit.html.twig', [
            'inscription' => $inscription,
            'params' => $this->parametres,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id<\d+>}/show', name: 'show')]
    public function show(Inscription $inscription): Response
    {
        return $this->render('inscription/show.html.twig', [
            'params' => $this->parametres,
            'inscrit' => $inscription,
        ]);
    }

    #[Route('/hosp_list', name: 'hosp_list')]
    public function hosp_list(InscriptionService $inscriptionService): Response
    {
        return $this->render('inscription/hosp_list.html.twig', [
            'params' => $this->parametres,
            'inscrits' => $inscriptionService->getPaginatedInscrits('H', false, 15),
        ]);
    }

    #[Route('/pmal_list', name: 'pmal_list')]
    public function liste_pmal(InscriptionService $inscriptionService): Response
    {
        return $this->render('inscription/pmal_list.html.twig', [
            'params' => $this->parametres,
            'inscrits' => $inscriptionService->getPaginatedInscrits('P', false, 15),
        ]);
    }

    #[Route('/lyce_list', name: 'lyce_list')]
    public function liste_lyce(InscriptionService $inscriptionService): Response
    {
        return $this->render('inscription/lyce_list.html.twig', [
            'params' => $this->parametres,
            'inscrits' => $inscriptionService->getPaginatedInscrits('L', false, 15),
        ]);
    }

    #[Route('/{id<\d+>}/edit_aff', name: 'edit_aff', methods: 'GET|POST')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit_aff(Inscription $inscription, Request $request): Response
    {
        $form = $this->createForm(AffectationType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /*
             * Mise à jour des info d'hébergement :
             * si isHebHosp est faux =>
             */
            if (!$inscription->isHebHosp()) {
                $inscription->setHebHotel('0');
            }

            $this->em->flush();
            // $this->addFlash('success', 'Affectation modifiée avec succès');

            return $this->redirectToRoute('insc.hosp_list');
        }

        return $this->render('affectation/edit.html.twig', [
            'inscription' => $inscription,
            'params' => $this->parametres,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id<\d+>}/show_aff', name: 'show_aff')]
    public function show_aff(Inscription $inscription): Response
    {
        $partChambre = [];

        if ($inscription->isHebHosp()) {
            $affectChambre = $this->inscriptionRepository->findChambrePart($inscription->getHebHotel(), $inscription->getHebChambre());

            foreach ($affectChambre as $partage) {
                $partChambre[] = $partage['prenom'].' '.$partage['nom'];
            }
        }

        return $this->render('affectation/show.html.twig', [
            'params' => $this->parametres,
            'inscription' => $inscription,
            'partChambre' => $partChambre,
        ]);
    }

    #[Route('/{id<\d+>}/{transition}/next', name: 'next')]
    #[IsGranted('ROLE_ADMIN')]
    public function next(Inscription $inscription, string $transition): Response
    {
        // $route = $this->requestStack->getCurrentRequest()->headers->get('Referer');

        $workflow = $this->registry->get($inscription);

        $date = new \DateTimeImmutable();

        switch ($transition) {
            case 'envoi_dossier':
                $inscription->setEnvoiAt($date->setTime(0, 0, 0));
                break;
            case 'retour_dossier':
                $inscription->setRetourAt($date->setTime(0, 0, 0));
                break;
            case 'validation_insc':
                $inscription->setValideAt($date->setTime(0, 0, 0));
                break;
            case 'insc_refuse':
            case 'insc_desist':
                // $inscription->affectation->remove();
                break;
            default:
        }

        if ($workflow->can($inscription, $transition)) {
            $workflow->apply($inscription, $transition);
        }

        $this->em->flush();

        //return $this->redirect($route);
        switch ($inscription->getEntite()) {
            case 0:
            case 1:
                return $this->redirectToRoute('insc.hosp_list');
                break;
            case 2:
            case 3:
                return $this->redirectToRoute('insc.lyce_list');
                break;
            case 4:
                return $this->redirectToRoute('insc.pmal_list');
                break;
            default:
        }
    }
}
