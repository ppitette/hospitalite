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
    name: 'app:user:deactivate',
    description: 'Désactiver un utilisateur'
)]
class UserDeactivateCommand extends Command
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
            ->setHelp(implode("\n", [
              'La commande <info>app:user:deactivate</info> désactive un utilisateur :',
              '<info>php %command.full_name% nom.prenom@hnde.org</info>',
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

        $user->setIsEnable(false);
        $this->em->flush();
        $io->success(sprintf('L\'utilisateur "%s" a été désactivé.', $email));

        return Command::SUCCESS;
    }
}
