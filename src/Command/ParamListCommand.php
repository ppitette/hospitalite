<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'app:param:list',
    description: 'Liste des paramètres de l\'application'
)]
class ParamListCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('domaine', InputArgument::REQUIRED, 'Domaine')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dom = $input->getArgument('domaine');

        $params = Yaml::parseFile('data/2023d.yaml');

        if ('tous' === $dom) {
            foreach ($params as $domaine => $tab) {
                $lgn = [];

                foreach ($tab as $key => $value) {
                    $lgn[] = [$key, $value['abr'], $value['lib'], $value['cmp']];
                }

                $bufferedOutput = new BufferedOutput();
                $io = new SymfonyStyle($input, $bufferedOutput);

                $io->title('Domaine : '.$domaine);
                $io->table(
                    ['Id', 'Abrégé', 'Libellé', 'Complément'],
                    $lgn,
                );

                $table = $bufferedOutput->fetch();
                $output->write($table);
                unset($lgn);
            }
        } else {
            $lgn = [];

            foreach ($params[$dom] as $key => $value) {
                $lgn[] = [$key, $value['abr'], $value['lib'], $value['cmp']];
            }

            $bufferedOutput = new BufferedOutput();
            $io = new SymfonyStyle($input, $bufferedOutput);

            $io->title('Domaine : '.$dom);
            $io->table(
                ['Id', 'Abrégé', 'Libellé', 'Complément'],
                $lgn
            );

            $table = $bufferedOutput->fetch();
            $output->write($table);
        }

        return Command::SUCCESS;
    }
}
