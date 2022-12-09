<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        /* warning if you enter au wrong password, the exception will be displayed. */
        if (!$user->getIsVerified()) {
            throw new CustomUserMessageAccountStatusException("Votre compte n'est pas actif, veuillez consulter vos emails pour l'activer avant le {$user->getMustBeVerifiedBefore()->format('d/m/Y à H\hi')}.");
        }

        if (!$user->getIsEnable()) {
            throw new CustomUserMessageAccountStatusException('Votre compte est désactivé.');
        }
    }
}
