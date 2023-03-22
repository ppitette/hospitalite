<?php

namespace App\Command;

use App\Service\Parametres;
use App\Entity\Participation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipationRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:pele:cloture',
    description: 'Clôture d\'un pèlerinage'
)]
class PeleClotureCommand extends Command
{
    CONST TEST = false;

    public function __construct(
        private EntityManagerInterface $em,
        private Parametres $parametres,
        private ParticipationRepository $participationRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp(implode("\n", [
                'La commande <info>app:pele:cloture</info> procède à la cloture du pèlerinage en cours :',
                '<info>php %command.full_name%</info>',
            ]))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pelerinage = $this->parametres->getPele();

        $anPele = $pelerinage->getDebut()->format("Y");

        $io = new SymfonyStyle($input, $output);

        if (self::TEST) {
            $io->title('TEST : Cloture du pèlerinage : '.$pelerinage->getAbrege());
        } else {
            $io->title('Cloture du pèlerinage : '.$pelerinage->getAbrege());
        }

        $inscriptions = $pelerinage->getInscriptions();

        $io->progressStart(\count($inscriptions));

        foreach ($inscriptions as $inscription) {
            $personne = $inscription->getPersonne();

            $participation = $this->participationRepository->findPartPele($personne, $pelerinage);

            if (!$participation) {
                $participation = (new Participation())->setPelerinage($pelerinage);
            }

            // Numéro d'inscription
            $participation->setNumInsc($inscription->getNumInsc());

            // Entité
            switch ($inscription->getEntite()) {
                case 0:
                case 1:
                case 2:
                    $participation->setEntite('HO');
                    break;
                case 3:
                    $participation->setEntite('LY');
                    break;
                case 4:
                    $participation->setEntite('PM');
                    break;
                default:
                    $participation->setEntite('__');
                    break;
            }

            // Age lors du pèlerinage
            $participation->setAgePele($personne->getAgeDate($pelerinage->getDebut()));

            // Hors effectif ?
            $hors_effectif = false;
            if ($inscription->isHorsEffectif()) {
                $hors_effectif = true;
            }

            $participation->setHorsEffectif($hors_effectif);

            // Voyage
            if ($inscription->isVoyAller() && $inscription->isVoyRetour()) {
                $participation->setVoyage('O');
            } elseif ($inscription->isVoyAller() && !$inscription->isVoyRetour()) {
                $participation->setVoyage('A');
            } elseif (!$inscription->isVoyAller() && $inscription->isVoyRetour()) {
                $participation->setVoyage('R');
            } else {
                $participation->setVoyage('N');
            }

            if ('pele_present' != $inscription->getCurrentPlace()) {
                // N'a pas pu participer
                $participation->setDesist(true);
            } else {
                // Présent au pèlerinage
                $participation->setDesist(false);

                // Hébergement
                if (!$inscription->isHebHosp()) {
                    $hebergement = '(*) '.$inscription->getHebPerso();
                } else {
                    $hebergement = $this->parametres->getLibelle('hotel', $inscription->getHebHotel());
                    if ($inscription->isHebSingle()) {
                        $hebergement .= ' (S)';
                    }
                }

                $participation->setHebergement($hebergement);

                // Responsabilité
                $resp = null;

                if ($inscription->getPeleResp() > 0) {
                    $resp = $this->parametres->getLibelle('resp', $inscription->getPeleResp());
                }

                $participation->setResp($resp);

                // Equipe médicale
                $medical = null;

                if ($personne->getMedical() > 0) {
                    $medical = $this->parametres->getLibelle('medical', $personne->getMedical());
                    if ($personne->getMedical() > 7) {
                        $medical = $medical . ' ' . $personne->getMedicalAutre();
                    }
                }

                // $io->note('MAJ :'.$personne->getNom().' '.$personne->getPrenom().' '.$resp.' '.$medical);

                // Groupe
                $participation->setGroupe('Groupe '.$this->parametres->getLibelle('groupe', $inscription->getGroupe()));
                
                if ($inscription->isNouveau()) {
                    $personne->setPPele($anPele);
                    $personne->setNbPele(1);
                    $personne->setDPele($anPele);
                } else {
                    $personne->setNbPele($personne->getNbPele() + 1);
                    $personne->setDPele($anPele);
                }

                if ($inscription->getEntite() == 3) {
                    $personne->setNbPele(1);
                }

                // Remise à null de l'inscription en cours
                $personne->setInscription(null);
            }

            $personne->addParticipation($participation);

            $io->progressAdvance();

            if (!self::TEST) {
                $this->em->persist($participation);
                $this->em->persist($personne);
                $this->em->flush();
            }
        }

        $io->progressFinish();

        $io->success(sprintf('Le pèlerinage "%s" a été cloturé.', $pelerinage->getAbrege()));

        return Command::SUCCESS;
    }
}
