<?php

namespace App\Controller;

use App\Repository\PersonneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/liste', name: 'liste.')]
class ListeController extends AbstractController
{
    public const LISTE_DECES_TABS = 12;

    #[Route('/deces', name: 'deces')]
    #[IsGranted('ROLE_USER')]
    public function deces(PersonneRepository $repository): Response
    {
        $deces = $repository->findDeces();

        return $this->render('liste/deces.html.twig', [
            'deces' => $deces,
            'max' => self::LISTE_DECES_TABS,
        ]);
    }
}
