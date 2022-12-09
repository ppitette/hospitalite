<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user:create',
    description: 'Créer un utilisateur'
)]
class UserCreateCommand extends Command
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->em = $em;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('firstname', InputArgument::REQUIRED, 'Prénom')
            ->addArgument('lastname', InputArgument::REQUIRED, 'Nom')
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe')
            ->addOption('super-admin', null, InputOption::VALUE_NONE, 'Définir l\'utilisateur comme super admin')
            ->addOption('inactive', null, InputOption::VALUE_NONE, 'Définir l\'utilisateur comme inactif')
            ->setHelp(implode("\n", [
                'La commande <info>app:user:create</info> crée un utilisateur :',
                '<info>php %command.full_name% Prénom Nom</info>',
                'Ce shell interactif vous demandera un email, puis un mot de passe.',
                'Vous pouvez également spécifier l\'email et le mot de passe comme deuxième et troisième arguments :',
                '<info>php %command.full_name% Prénom Nom nom.prenom@hnde.org mot_de_passe</info>',
                'Affecter le rôle super administrateur avec l\'option <comment>--super-admin</comment> :',
                '<info>php %command.full_name%</info> <comment>--super-admin</comment>',
                'Créer un utilisateur inactif avec l\'option <comment>--inactive</comment> :',
                '<info>php %command.full_name%</info> <comment>--inactive</comment>',
            ]))
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $questions = [];

        if (!$input->getArgument('firstname')) {
            $question = new Question('Veuillez entrer le prénom :');
            $question->setValidator(function ($firstname) {
                if (empty($firstname)) {
                    throw new \Exception('Le prénom ne peut pas être vide');
                }

                return $firstname;
            });
            $questions['firstname'] = $question;
        }

        if (!$input->getArgument('lastname')) {
            $question = new Question('Veuillez entrer le nom :');
            $question->setValidator(function ($lastname) {
                if (empty($lastname)) {
                    throw new \Exception('Le nom ne peut pas être vide');
                }

                return $lastname;
            });
            $questions['lastname'] = $question;
        }

        if (!$input->getArgument('email')) {
            $question = new Question('Veuillez entrer un e-mail :');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('L\'e-mail ne peut pas être vide');
                }
                if ($this->userRepository->findOneByEmail($email)) {
                    throw new \Exception('L\'e-mail est déjà utilisé');
                }

                return $email;
            });
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Veuillez choisir un mot de passe :');
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
        $user = new User();
        $user
            ->setFirstname($input->getArgument('firstname'))
            ->setLastname($input->getArgument('lastname'))
            ->setEmail($email);

        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $input->getArgument('password')
            )
        );

        if ($input->getOption('inactive')) {
            $user->setIsEnable(false);
        } else {
            $user->setIsEnable(true);
        }

        if ($input->getOption('super-admin')) {
            $user->setRoles(['ROLE_SUPER_ADMIN']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }

        $this->em->persist($user);
        $this->em->flush();

        $io->success(sprintf('Utilisateur créé avec l\'e-mail %s.', $email));

        return Command::SUCCESS;
    }
}
