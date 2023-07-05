<?php

namespace App\Controller;

use App\Service\Parametres;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\InscriptionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/graph', name: 'graph.')]
#[IsGranted('ROLE_LECT')]
class GraphController extends AbstractController
{
    public function __construct(
        private InscriptionRepository $inscriptionRepository,
        private Parametres $parametres,
    ) {
    }

    #[Route('/insc', name: 'insc')]
    public function graph(ChartBuilderInterface $chartBuilder): Response
    {
        $statLab = [0, 1, 2, 3, 4];

        foreach ($statLab as $ent) {
            $statInsc[] = $this->inscriptionRepository->dataGraph01($ent, false, false);
            $statHe[] = $this->inscriptionRepository->dataGraph01($ent, false, true);
            $statLa[] = $this->inscriptionRepository->dataGraph01($ent, true, false);
        }

        $chart00 = $chartBuilder->createChart(Chart::TYPE_BAR);

        $chart00->setData([
            'labels' => ['Hospitaliers', 'Hospitaliers Ind', 'Enc. Lycéens', 'Lycéens', 'Pers. Malades'],
            'datasets' => [
                [
                    'label' => 'Inscrits',
                    'data' => $statInsc,
                    'backgroundColor' => '#00a8ff',
                ],
                [
                    'label' => 'Hors Effectif',
                    'data' => $statHe,
                    'backgroundColor' => '#fbc531',
                ],
                [
                    'label' => 'Liste d\'attente',
                    'data' => $statLa,
                    'backgroundColor' => '#e84118',
                ],
            ],
        ]);

        $chart00->setOptions([
            'plugin' => [
                'title' => [
                    ['display' => false],
                ],
            ],
            'scales' => [
                'xAxes' => [
                    ['stacked' => true],
                ],
                'yAxes' => [
                    ['stacked' => true],
                ],
            ],
        ]);

        $tabTransp = $this->inscriptionRepository->inscVoyage();

        $statLab = [
            'insc_enreg', 'doss_envoye', 'doss_retourne', 'insc_valide', 'insc_refuse', 'insc_confirme',
        ];

        $chart11 = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        foreach ($statLab as $etval) {
            $statDossHosp[] = $this->inscriptionRepository->dataGraph02(0, $etval)
                + $this->inscriptionRepository->dataGraph02(1, $etval);
        }

        $chart11->setData([
            'labels' => [
                'Enregistré', 'Doss Envoyé', 'Doss retourné', 'Doss validé', 'Doss refusé', 'Confirmé',
            ],
            'datasets' => [
                [
                    'label' => 'Inscrits',
                    'data' => $statDossHosp,
                    'backgroundColor' => [
                        '#00a8ff', '#9c88ff', '#fbc531', '#4cd137', '#e84118', '#487eb0',
                    ],
                ],
            ],
        ]);

        $chart11->setOptions([
            'plugin' => [
                'title' => [
                    ['display' => false],
                ],
            ],
        ]);

        $chart12 = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        foreach ($statLab as $etval) {
            $statDossLyce[] = $this->inscriptionRepository->dataGraph02(2, $etval)
                + $this->inscriptionRepository->dataGraph02(3, $etval);
        }

        $chart12->setData([
            'labels' => [
                'Enregistré', 'Doss Envoyé', 'Doss retourné', 'Doss validé', 'Doss refusé', 'Confirmé',
            ],
            'datasets' => [
                [
                    'label' => 'Inscrits',
                    'data' => $statDossLyce,
                    'backgroundColor' => [
                        '#00a8ff', '#9c88ff', '#fbc531', '#4cd137', '#e84118', '#487eb0',
                    ],
                ],
            ],
        ]);

        $chart12->setOptions([
            'plugin' => [
                'title' => [
                    ['display' => false],
                ],
            ],
        ]);

        $chart13 = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        foreach ($statLab as $etval) {
            $statDossMlde[] = $this->inscriptionRepository->dataGraph02(4, $etval);
        }

        $chart13->setData([
            'labels' => [
                'Enregistré', 'Doss Envoyé', 'Doss retourné', 'Doss validé', 'Doss refusé', 'Confirmé',
            ],
            'datasets' => [
                [
                    'label' => 'Inscrits',
                    'data' => $statDossMlde,
                    'backgroundColor' => [
                        '#00a8ff', '#9c88ff', '#fbc531', '#4cd137', '#e84118', '#487eb0',
                    ],
                ],
            ],
        ]);

        $chart13->setOptions([
            'plugin' => [
                'title' => [
                    ['display' => false],
                ],
            ],
        ]);

        return $this->render('graph/insc.html.twig', [
            'params' => $this->parametres,
            'tabTransp' => $tabTransp,
            'chart00' => $chart00,
            'chart11' => $chart11,
            'chart12' => $chart12,
            'chart13' => $chart13,
        ]);
    }
}
