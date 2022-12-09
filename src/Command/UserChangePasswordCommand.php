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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user:change-password',
    description: 'Changer le mot de passe d\'un utilisateur'
)]
class UserChangePasswordCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'E-mail')
            ->addArgument('password', InputArgument::REQUIRED, 'Nouveau mot de passe')
            ->setHelp(implode("\n", [
                'La commande <info>app:user:change-password</info> change le mot de passe d\'un utilisateur :',
                '<info>php %command.full_name% nom.prenom@hnde.org</info>',
                'Ce shell interactif vous demandera d\'abord un mot de passe.',
                'Vous pouvez alternativement spécifier le mot de passe comme second argument :',
                '<info>php %command.full_name% nom.prenom@hnde.org mot_de_passe</info>',
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

        if (!$input->getArgument('password')) {
            $question = new Question('Veuillez entrer le nouveau mot de passe :');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Le mot de passe ne peut pas être vide');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
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
        $user = $this->userRepository->findOneByEmail($email);

        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            $input->getArgument('password')
        ));

        $this->em->flush();

        $io->success(sprintf('Mot de passe changé pour l\'utilisateur %s.', $email));

        return Command::SUCCESS;
    }
}
