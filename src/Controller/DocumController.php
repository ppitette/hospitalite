<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Service\Parametres;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Repository\InscriptionRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/docum', name: 'doc.')]
#[IsGranted('ROLE_LECT')]
class DocumController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private InscriptionRepository $inscriptionRepository,
        private Parametres $parametres,
    ) {
    }

    #[Route('/{id<\d+>}/doss_hosp', name: 'doss_hosp')]
    #[IsGranted('ROLE_ADMIN')]
    public function doss_hosp(Inscription $inscription): BinaryFileResponse
    {
        $dir = $this->getParameter('app.docum_directory');
        $templateProcessor = new TemplateProcessor($dir.'/templates/Dossier_H_Tmpl.docx');

        $formule = 'Cher ami,';
        if (1 === $inscription->getPersonne()->getGenre()) {
            $formule = 'Cher ami Hospitalier,';
        } else {
            $formule = 'Chère amie Hospitalière,';
        }

        $adresse = $inscription->getPersonne()->getAdresse()->getLibAdresse();
        $adresse = str_replace("\n", '<w:br/>', $adresse);

        $paroisse = $inscription->getPersonne()->getAdresse()->getParoisse();
        if ('Diocèse d\'Évreux' !== $inscription->getPersonne()->getAdresse()->getDiocese()) {
            $paroisse = $inscription->getPersonne()->getAdresse()->getDiocese();
        }

        $telephone = $this->format_phone($inscription->getPersonne()->getTelephone());
        $mobile = $this->format_phone($inscription->getPersonne()->getMobile());

        $templateProcessor->setValues([
            'civilite' => $inscription->getPersonne()->getCivilite(),
            'prenom' => $inscription->getPersonne()->getPrenom(),
            'nom' => $inscription->getPersonne()->getNom(),
            'adresse' => $adresse,
            'formule' => $formule,
            'insc' => $inscription->getNumInsc(),
            'all' => ($inscription->IsVoyAller() ? 'Oui' : 'Non'),
            'ret' => ($inscription->isVoyRetour() ? 'Oui' : 'Non'),
            'naissance' => $inscription->getPersonne()->getDateNaiss()->format('d/m/Y'),
            'conjoint' => $inscription->getConjoint(),
            'courriel' => $inscription->getPersonne()->getCourriel(),
            'telephone' => $telephone,
            'mobile' => $mobile,
            'paroisse' => $paroisse,
        ]);

        $docum = 'doss_'.$inscription->getPersonne()->getNom().'_'.$inscription->getPersonne()->getPrenom().'.docx';
        $fileName = '../public/download/'.$docum;

        $templateProcessor->saveAs($fileName);

        return $this->file($fileName);
    }

    #[Route('/{id<\d+>}/doss_elyc', name: 'doss_elyc')]
    #[IsGranted('ROLE_ADMIN')]
    public function doss_elyc(Inscription $inscription): BinaryFileResponse
    {
        $dir = $this->getParameter('app.docum_directory');
        $templateProcessor = new TemplateProcessor($dir.'/templates/Dossier_E_Tmpl.docx');

        $formule = 'Cher ami,';
        if (1 === $inscription->getPersonne()->getGenre()) {
            $formule = 'Cher ami Hospitalier,';
        } else {
            $formule = 'Chère amie Hospitalière,';
        }

        $adresse = $inscription->getPersonne()->getAdresse()->getLibAdresse();
        $adresse = str_replace("\n", '<w:br/>', $adresse);

        $telephone = $this->format_phone($inscription->getPersonne()->getTelephone());
        $mobile = $this->format_phone($inscription->getPersonne()->getMobile());

        $pele = $this->parametres->getPele();
        $debPele = $pele->getDebut();

        $templateProcessor->setValues([
            'dest' => $inscription->getMinDest(),
            'civilite' => $inscription->getPersonne()->getCivilite(),
            'prenom' => $inscription->getPersonne()->getPrenom(),
            'nom' => $inscription->getPersonne()->getNom(),
            'adresse' => $adresse,
            'formule' => $formule,
            'insc' => $inscription->getNumInsc(),
            'all' => ($inscription->IsVoyAller() ? 'Oui' : 'Non'),
            'ret' => ($inscription->IsVoyRetour() ? 'Oui' : 'Non'),
            'naissance' => $inscription->getPersonne()->getDateNaiss()->format('d/m/Y'),
            'age' => $inscription->getPersonne()->getAgeDate($debPele),
            'courriel' => $inscription->getPersonne()->getCourriel(),
            'telephone' => $telephone,
            'mobile' => $mobile,
        ]);

        $docum = 'doss_'.$inscription->getPersonne()->getNom().'_'.$inscription->getPersonne()->getPrenom().'.docx';
        $fileName = '../public/download/'.$docum;

        $templateProcessor->saveAs($fileName);

        return $this->file($fileName);
    }

    #[Route('/{id<\d+>}/doss_lyce', name: 'doss_lyce')]
    #[IsGranted('ROLE_ADMIN')]
    public function doss_lyce(Inscription $inscription): BinaryFileResponse
    {
        $dir = $this->getParameter('app.docum_directory');
        $templateProcessor = new TemplateProcessor($dir.'/templates/Dossier_L_Tmpl.docx');

        $adresse = $inscription->getPersonne()->getAdresse()->getLibAdresse();
        $adresse = str_replace("\n", '<w:br/>', $adresse);

        $telephone = $this->format_phone($inscription->getPersonne()->getTelephone());
        $mobile = $this->format_phone($inscription->getPersonne()->getMobile());

        $pele = $this->parametres->getPele();
        $debPele = $pele->getDebut();

        $templateProcessor->setValues([
            'dest' => $inscription->getMinDest(),
            'civilite' => $inscription->getPersonne()->getCivilite(),
            'prenom' => $inscription->getPersonne()->getPrenom(),
            'nom' => $inscription->getPersonne()->getNom(),
            'adresse' => $adresse,
            'insc' => $inscription->getNumInsc(),
            'all' => ($inscription->IsVoyAller() ? 'Oui' : 'Non'),
            'ret' => ($inscription->IsVoyRetour() ? 'Oui' : 'Non'),
            'naissance' => $inscription->getPersonne()->getDateNaiss()->format('d/m/Y'),
            'age' => $inscription->getPersonne()->getAgeDate($debPele),
            'courriel' => $inscription->getPersonne()->getCourriel(),
            'telephone' => $telephone,
            'mobile' => $mobile,
        ]);

        $docum = 'doss_'.$inscription->getPersonne()->getNom().'_'.$inscription->getPersonne()->getPrenom().'.docx';
        $fileName = '../public/download/'.$docum;

        $templateProcessor->saveAs($fileName);

        return $this->file($fileName);
    }

    #[Route('/{id<\d+>}/doss_pmal', name: 'doss_pmal')]
    #[IsGranted('ROLE_ADMIN')]
    public function doss_pmal(Inscription $inscription): BinaryFileResponse
    {
        $dir = $this->getParameter('app.docum_directory');
        $templateProcessor = new TemplateProcessor($dir.'/templates/Dossier_M_Tmpl.docx');

        $adresse = $inscription->getPersonne()->getAdresse()->getLibAdresse();
        $adresse = str_replace("\n", '<w:br/>', $adresse);

        $telephone = $this->format_phone($inscription->getPersonne()->getTelephone());
        $mobile = $this->format_phone($inscription->getPersonne()->getMobile());

        $pele = $this->parametres->getPele();
        $debPele = $pele->getDebut();

        $templateProcessor->setValues([
            'civilite' => $inscription->getPersonne()->getCivilite(),
            'prenom' => $inscription->getPersonne()->getPrenom(),
            'nom' => $inscription->getPersonne()->getNom(),
            'adresse' => $adresse,
            'insc' => $inscription->getNumInsc(),
            'naissance' => $inscription->getPersonne()->getDateNaiss()->format('d/m/Y'),
            'age' => $inscription->getPersonne()->getAgeDate($debPele),
            'courriel' => $inscription->getPersonne()->getCourriel(),
            'telephone' => $telephone,
            'mobile' => $mobile,
        ]);

        $docum = 'doss_'.$inscription->getPersonne()->getNom().'_'.$inscription->getPersonne()->getPrenom().'.docx';
        $fileName = '../public/download/'.$docum;

        $templateProcessor->saveAs($fileName);

        return $this->file($fileName);
    }

    #[Route('/{id<\d+>}/acpt_pmal', name: 'acpt_pmal')]
    #[IsGranted('ROLE_ADMIN')]
    public function doss_acpt(Inscription $inscription): BinaryFileResponse
    {
        $dir = $this->getParameter('app.docum_directory');
        $templateProcessor = new TemplateProcessor($dir.'/templates/Acceptation_M_Tmpl.docx');

        $adresse = $inscription->getPersonne()->getAdresse()->getLibAdresse();
        $adresse = str_replace("\n", '<w:br/>', $adresse);

        $pele = $this->parametres->getPele();
        $debPele = $pele->getDebut();

        $templateProcessor->setValues([
            'civilite' => $inscription->getPersonne()->getCivilite(),
            'prenom' => $inscription->getPersonne()->getPrenom(),
            'nom' => $inscription->getPersonne()->getNom(),
            'adresse' => $adresse,
            'insc' => $inscription->getNumInsc(),
        ]);

        $docum = 'acpt_'.$inscription->getPersonne()->getNom().'_'.$inscription->getPersonne()->getPrenom().'.docx';
        $fileName = '../public/download/'.$docum;

        $templateProcessor->saveAs($fileName);

        return $this->file($fileName);
    }

    private function format_phone(?string $phone): string
    {
        $format_phone = ' ';

        if ($phone) {
            if (preg_match('#^0[1-9]{1}\d{8}$#', $phone)) {
                $format_phone = wordwrap($phone, 2, ' ', true);
            }
        }

        return $format_phone;
    }
}
