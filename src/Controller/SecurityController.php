<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SendEmail;
use App\Form\ResetPasswordType;
use App\Form\ForgotPasswordType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    private $session;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack,
        private UserRepository $userRepository,
        private SendEmail $sendEmail,
        private TokenGeneratorInterface $tokenGenerator,
    ) {
    }

    #[Route('/login', name: 'app.login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app.home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/logout', name: 'app.logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank, it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgot-password', name: 'app.forgot_password', methods: 'GET|POST')]
    public function sendRecoveryLink(Request $request): Response {
        $form = $this->createForm(ForgotPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userRepository->findOneBy([
                'email' => $form['email']->getData(),
            ]);

            /* Création d'un leure */
            if (!$user) {
                $this->addFlash('success', 'Un email vous a été envoyé pour redéfinir votre mot de passe.');

                return $this->redirectToRoute('app.home');
            }

            $user->setFrgtPswdToken($this->tokenGenerator->generateToken())
                 ->setFrgtPswdTokenRequestedAt(new \DateTimeImmutable('now'))
                 ->setFrgtPswdTokenMustBeVerifiedBefore(new \DateTimeImmutable('+15 minutes'));

            $this->entityManager->flush();

            $this->sendEmail->send([
                'recipient_email' => $user->getEmail(),
                'subject' => 'Modification de votre mot de passe',
                'html_template' => 'security/forgot_password_email.html.twig',
                'context' => [
                    'user' => $user,
                ],
            ]);

            if ($user) {
                $this->addFlash('success', 'Un email vous a été envoyé pour redéfinir votre mot de passe.');

                return $this->redirectToRoute('app.login');
            }
        }

        return $this->render('security/forgot_password_step_1.html.twig', [
            'forgotPasswordFormStep1' => $form->createView(),
        ]);
    }

    #[Route('/forgot-password/{id<\d+>}/{token}', name: 'app.retrieve_credentials', methods: 'GET')]
    public function retrieveCredentialsFromURL(string $token, User $user): RedirectResponse {
        $this->session->set('Reset-Password-Token-URL', $token);
        $this->session->set('Reset-Password-User-Email', $user->getEmail());

        return $this->redirectToRoute('app.reset_password');
    }

    #[Route('/reset-password', name: 'app.reset_password', methods: 'GET|POST')]
    public function resetPassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        [
            'token' => $token,
            'userEmail' => $userEmail
        ] = $this->getCredentialsFromSession();

        $user = $this->userRepository->findOneBy([
            'email' => $userEmail,
        ]);

        if (!$user) {
            return $this->redirectToRoute('app.forgot_password');
        }

        /** @var \DateTimeImmutable $frgtPswdTokenMustBeVerifiedBefore */
        $frgtPswdTokenMustBeVerifiedBefore = $user->getFrgtPswdTokenMustBeVerifiedBefore();

        if ((null === $user->getFrgtPswdToken()) || ($user->getFrgtPswdToken() !== $token) || ($this->isNotRequestedInTime($frgtPswdTokenMustBeVerifiedBefore))) {
            return $this->redirectToRoute('app.forgot_password');
        }

        $form = $this->createForm(ResetPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $form['password']->getData()));

            $user->setFrgtPswdToken(null)
                 ->setFrgtPswdTokenVerifiedAt(new \DateTimeImmutable('now'));

            $this->entityManager->flush();
            $this->removeCredentialsFromSession();

            $this->addFlash('success', 'Votre mot de passe a bien été modifié, vous pouvez de nouveau vous connecter.');

            return $this->redirectToRoute('app.login');
        }

        return $this->render('security/forgot_password_step_2.html.twig', [
            'forgotPasswordFormStep2' => $form->createView(),
            'passwordMustBeVerifiedBefore' => $this->passwordMustbeVerifiedBefore($user),
        ]);
    }

    #[Route('/register', name: 'app.register', methods: 'GET|POST')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registrationToken = $this->tokenGenerator->generateToken();
            $user->setRegistrationToken($registrationToken)
                 ->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->sendEmail->send([
                'recipient_email' => $user->getEmail(),
                'subject' => 'Vérification de votre adresse email pour activer votre compte utilisateur',
                'html_template' => 'security/register_confirmation_email.html.twig',
                'context' => [
                    'userID' => $user->getId(),
                    'registrationToken' => $registrationToken,
                    'tokenLifeTime' => $user->getMustBeVerifiedBefore()->format('d/m/Y à H:i'),
                ],
            ]);

            $this->addFlash('success', "Votre compte utilisateur a bien été créé, veuillez consulter vos emails pour l'activer.");

            return $this->redirectToRoute('app.login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route("/{id<\d+>}/{token}", name: 'app.verify_account', methods: 'GET')]
    public function verifyUserAccount(
        User $user,
        string $token
    ): Response {
        if (
            (null === $user->getRegistrationToken()) ||
            ($user->getRegistrationToken() !== $token) ||
            ($this->isNotRequestedInTime($user->getMustBeVerifiedBefore()))
        ) {
            throw new AccessDeniedException();
        }

        $user->setIsVerified(true);
        $user->setVerifiedAt(new \DateTimeImmutable('now'));
        $user->setRegistrationToken(null);

        $this->entityManager->flush();

        $this->addFlash('success', 'Votre compte utilisateur est bien activé, vous pouvez vous connecter.');

        return $this->redirectToRoute('app.login');
    }

    /**
     * Add the user ID and the token in the session.
     */
    private function getCredentialsFromSession(): array
    {
        return [
            'token' => $this->session->get('Reset-Password-Token-URL'),
            'userEmail' => $this->session->get('Reset-Password-User-Email'),
        ];
    }

    /**
     * Remove the user ID and the token from the session.
     */
    private function removeCredentialsFromSession(): void
    {
        $this->session->remove('Reset-Password-Token-URL');
        $this->session->remove('Reset-Password-User-Email');
    }

    /**
     * Validate or not the fact that the link was clicked in the alloted time.
     */
    private function isNotRequestedInTime(\DateTimeImmutable $mustBeVerifiedBefore): bool
    {
        return new \DateTimeImmutable('now') > $mustBeVerifiedBefore;
    }

    /**
     * returns the time before witch the password mus be modified.
     *
     * @return string The time in this format: 12h01
     */
    private function passwordMustbeVerifiedBefore(User $user): string
    {
        /** @var \DateTimeImmutable $passwordMustBeVerifiedBefore */
        $passwordMustBeVerifiedBefore = $user->getFrgtPswdTokenMustBeVerifiedBefore();

        return $passwordMustBeVerifiedBefore->format('H\hi');
    }
}
