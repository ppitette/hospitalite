<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user:demote',
    description: 'Supprimer un rôle à un utilisateur'
)]
class UserDemoteCommand extends Command
{
    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'E-mail')
            ->addArgument('role', InputArgument::REQUIRED, 'Rôle à supprimer')
            ->setHelp(implode("\n", [
                'La commande <info>app:user:demote</info> supprime un rôle à un utilisateur :',
                '<info>php %command.full_name% nom.prenom@hnde.org</info>',
                'Ce shell interactif vous demandera d\'abord un rôle.',
                'Vous pouvez également spécifier le rôle comme second argument :',
                '<info>php %command.full_name% nom.prenom@hnde.org ROLE_ADMIN</info>',
            ]))
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $questions = [];

        if (!$input->getArgument('email')) {
            $question = new Question('Veuillez indiquer l\'e-mail :');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('L\'e-mail ne peut pas être vide');
                }

                if (!$this->userRepository->findOneByEmail($email)) {
                    throw new \Exception('Aucun utilisateur trouvé avec cet e-mail');
                }

                return $email;
            });
            $questions['email'] = $question;
        }

        if (!$input->getArgument('role')) {
            $question = new Question('Veuillez entrer le rôle à supprimer :');
            $question->setValidator(function ($role) {
                if (empty($role)) {
                    throw new \Exception('Le rôle ne peut pas être vide');
                }

                return $role;
            });
            $questions['role'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $role = $input->getArgument('role');
        $user = $this->userRepository->findOneByEmail($email);

        $roles = $user->getRoles();

        if (!\in_array($role, $roles, true)) {
            $io->error(sprintf("L\'utilisateur %s n'a pas le rôle %s.", $email, $role));

            return 1;
        }
        array_splice($roles, array_search($role, $roles, true), 1);
        $user->setRoles($roles);
        $this->em->flush();
        $io->success(sprintf('Le rôle %s a été retiré à l\'utilisateur %s.', $role, $email));

        return 0;
    }
}
