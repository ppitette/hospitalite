<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user:list',
    description: 'Lister les utilisateurs',
)]
class UserListCommand extends Command
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('max-results', null, InputOption::VALUE_OPTIONAL, 'Limite le nombre d\'utilisateurs listés', 50)
            ->setHelp(implode("\n", [
                'La commande <info>app:user:list</info> liste tous les utilisateurs enregistrés :',
                '<info>php %command.full_name%</info>',
                'Par défaut, la commande n\'affiche que les 50 utilisateurs les plus récents.',
                'Définissez le nombre de résultats à afficher avec l\'option <comment>--max-results</comment> :',
                '<info>php %command.full_name%</info> <comment>--max-results=2000</comment>',
            ]))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $maxResults = $input->getOption('max-results');
        $allUsers = $this->userRepository->findBy([], ['id' => 'ASC'], $maxResults);

        $usersAsPlainArrays = array_map(function (User $user) {
            return [
                $user->getId(),
                $user->getFirstName(),
                $user->getLastName(),
                $user->getEmail(),
                implode(', ', $user->getRoles()),
                $user->getIsVerified(),
                $user->getIsEnable(),
            ];
        }, $allUsers);

        $bufferedOutput = new BufferedOutput();

        $io = new SymfonyStyle($input, $bufferedOutput);

        $io->table(
            ['Id', 'Prénom', 'Nom', 'E-mail', 'Roles', 'Vérif', 'Activ'],
            $usersAsPlainArrays
        );

        $usersAsATable = $bufferedOutput->fetch();
        $output->write($usersAsATable);

        return Command::SUCCESS;
    }
}
