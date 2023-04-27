<?php

namespace App\Controller;

use App\Repository\AdresseRepository;
use App\Repository\InscriptionRepository;
use App\Repository\PersonneRepository;
use App\Service\Parametres;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/export', name: 'export.')]
#[IsGranted('ROLE_ADMIN')]
class ExportController extends AbstractController
{
    public function __construct(
        private Parametres $parametres
    ) {
    }

    #[Route('/mailing', name: 'mailing')]
    public function mlist(PersonneRepository $personne): BinaryFileResponse
    {
        $fileName = '../public/download/mailing_list.csv';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(0);

        $sheet
            ->setCellValue('A1', 'Email')
            ->setCellValue('B1', 'Newsletter_insc')
            ->setCellValue('C1', 'Jeunes_18_30')
            ->setCellValue('D1', 'Dpele')
            ->setCellValue('E1', 'Firstname')
            ->setCellValue('F1', 'Lastname')
            ->setCellValue('G1', 'Token');

        $list = $personne->extractMList();

        $an = date('Y');
        $rowCount = 2;

        foreach ($list as $elem) {
            $sheet->setCellValue('A'.$rowCount, $elem['courriel']);
            $sheet->setCellValue('B'.$rowCount, $elem['liste']);
            $sheet->setCellValue('C'.$rowCount, 0);
            $age = 0;
            if ($elem['dateNaiss']) {
                $age = $an - $elem['dateNaiss']->format('Y');
            }
            if ($age > 17 && $age < 31) {
                $sheet->setCellValue('C'.$rowCount, 1);
            }
            $sheet->setCellValue('D'.$rowCount, $elem['dPele']);
            $sheet->setCellValue('E'.$rowCount, $elem['prenom']);
            $sheet->setCellValue('F'.$rowCount, $elem['nom']);
            $sheet->setCellValue('G'.$rowCount, $elem['id']);
            ++$rowCount;
        }

        $writer = new Csv($spreadsheet);
        $writer->setUseBOM(true);
        $writer->setOutputEncoding('UTF-8');
        $writer->setEnclosureRequired(false);
        $writer->setSheetIndex(0);
        $writer->save($fileName);

        return $this->file($fileName);
    }

    #[Route('/hospit', name: 'hospit')]
    public function hlist(PersonneRepository $personne): BinaryFileResponse
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Hospitalite');
        $sheet = $spreadsheet->getActiveSheet(0);

        $sheet
            ->setCellValue('A1', 'Hospitalier')
            ->setCellValue('B1', 'Adresse')
            ->setCellValue('C1', 'Telephone')
            ->setCellValue('D1', 'Mobile')
            ->setCellValue('E1', 'Courriel')
            ->setCellValue('F1', 'Eng')
            ->setCellValue('G1', 'EngHosp')
            ->setCellValue('H1', 'EngEgl')
            ->setCellValue('I1', 'PPele')
            ->setCellValue('J1', 'NbPele')
            ->setCellValue('K1', 'DPele')
            ->setCellValue('L1', 'DNaiss')
            ->setCellValue('M1', 'Paroisse')
            ->setCellValue('N1', 'Insc')
        ;

        $list = $personne->extractHList();

        $rowCount = 2;

        foreach ($list as $elem) {
            $sheet->setCellValue('A'.$rowCount, trim($elem['nom'].' '.$elem['prenom']));

            $adresse = $this->formatAdr(
                $elem['remise'],
                $elem['compLoc'],
                $elem['numVoie'],
                $elem['typeVoie'],
                $elem['nomVoie'],
                $elem['compVoie'],
                $elem['cPostal'],
                $elem['commune'],
                $elem['pays']
            );
            $sheet->setCellValue('B'.$rowCount, $adresse);

            $sheet->setCellValue('C'.$rowCount, $elem['telephone']);
            $sheet->setCellValue('D'.$rowCount, $elem['mobile']);

            $email = $elem['courriel'];
            if ($elem['lrCourriel']) {
                $email = 'Liste rouge';
            }

            $sheet->setCellValue('E'.$rowCount, $email);

            $sheet->setCellValue('G'.$rowCount, $elem['engHosp']);
            $sheet->setCellValue('H'.$rowCount, $elem['engEgl']);
            $sheet->setCellValue('I'.$rowCount, $elem['pPele']);
            $sheet->setCellValue('J'.$rowCount, $elem['nbPele']);
            $sheet->setCellValue('K'.$rowCount, $elem['dPele']);
            $sheet->setCellValue('L'.$rowCount, $elem['dateNaiss']->format('d/m/Y'));

            $sheet->setCellValue('M'.$rowCount, $elem['paroisse']);
            if ($elem['diocese'] != 'Diocèse d\'Évreux') {
                $sheet->setCellValue('M'.$rowCount, $elem['diocese']);
            }

            $sheet->setCellValue('N'.$rowCount, $elem['numInsc']);

            ++$rowCount;
        }

        $spreadsheet->getActiveSheet()->setTitle('Liste');
        $spreadsheet->setActiveSheetIndex(0);

        $fileName = '../public/download/hosp_list.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);

        return $this->file($fileName);
    }

    #[Route('/inscrits', name: 'inscrits')]
    public function insc(InscriptionRepository $inscription, AdresseRepository $adresse): BinaryFileResponse
    {
        $fileName = '../public/download/inscriptions.xlsx';

        $an = date('Y');

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Hospitalite');
        $sheet = $spreadsheet->getActiveSheet(0);

        $sheet
            ->setCellValue('A1', 'insc')
            ->setCellValue('B1', 'date')
            ->setCellValue('C1', 'ent')
            ->setCellValue('D1', 'all')
            ->setCellValue('E1', 'ret')
            ->setCellValue('F1', 'civ')
            ->setCellValue('G1', 'nom')
            ->setCellValue('H1', 'prenom')
            ->setCellValue('I1', 'adresse')
            ->setCellValue('J1', 'fixe')
            ->setCellValue('K1', 'mobile')
            ->setCellValue('L1', 'courriel')
            ->setCellValue('M1', 'couple')
            ->setCellValue('N1', 'naissance')
            ->setCellValue('O1', 'paroisse')
            ->setCellValue('P1', 'urgence');

        $list = $inscription->extractInscList();

        $rowCount = 2;

        foreach ($list as $elem) {
            $sheet->setCellValue('A'.$rowCount, $elem['numInsc']);
            $sheet->setCellValue('B'.$rowCount, $elem['inscritAt']->format('d/m/Y'));

            $sheet->setCellValue('C'.$rowCount, $this->parametres->getAbrege('entite', $elem['entite']));

            $sheet->setCellValue('D'.$rowCount, 'Non');
            if ($elem['voyAller']) {
                $sheet->setCellValue('D'.$rowCount, 'Oui');
            }

            $sheet->setCellValue('E'.$rowCount, 'Non');
            if ($elem['voyRetour']) {
                $sheet->setCellValue('E'.$rowCount, 'Oui');
            }

            $sheet->setCellValue('F'.$rowCount, $elem['civilite']);
            $sheet->setCellValue('G'.$rowCount, $elem['nom']);
            $sheet->setCellValue('H'.$rowCount, $elem['prenom']);

            $data = '';
            if ($adresse->find($elem['id'])) {
                $data = $adresse->find($elem['id'])->getLibAdresse();
            }
            $sheet->setCellValue('I'.$rowCount, $data);

            $sheet->setCellValue('J'.$rowCount, $elem['telephone']);
            $sheet->setCellValue('K'.$rowCount, $elem['mobile']);
            $sheet->setCellValue('L'.$rowCount, $elem['courriel']);
            $sheet->setCellValue('M'.$rowCount, $elem['conjoint']);
            $sheet->setCellValue('N'.$rowCount, $elem['dateNaiss']->format('d/m/Y'));

            $sheet->setCellValue('O'.$rowCount, $elem['paroisse']);
            if ('Diocèse d\'Évreux' !== $elem['diocese']) {
                $sheet->setCellValue('O'.$rowCount, $elem['diocese']);
            }

            $sheet->setCellValue('P'.$rowCount, $elem['personneUrgence']);

            ++$rowCount;
        }

        $spreadsheet->getActiveSheet()->setTitle('Liste');
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);

        return $this->file($fileName);
    }

    private function formatAdr($remise, $compLoc, $numVoie, $typeVoie, $nomVoie, $compVoie, $cPostal, $commune, $pays)
    {
        $adresse = '';

        if ($remise) {
            $adresse .= $remise."\n";
        }

        if ($compLoc) {
            $adresse .= $compLoc."\n";
        }

        if ($numVoie) {
            $adresse .= $numVoie.' ';
        }

        if ($typeVoie) {
            $adresse .= $typeVoie.' ';
        }

        if ($nomVoie) {
            $adresse .= $nomVoie."\n";
        }

        if ($compVoie) {
            $adresse .= $compVoie."\n";
        }

        if ($cPostal) {
            $adresse .= $cPostal.' ';
        }

        if ($commune) {
            $adresse .= $commune;
        }

        if ($pays && 'France' !== $pays) {
            $adresse .= "\n".$pays;
        }

        return $adresse;
    }
}
