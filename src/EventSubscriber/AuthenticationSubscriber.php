<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private RequestStack $requestStack
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccessEvent',
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }

    public function onLoginSuccessEvent(LoginSuccessEvent $event): void
    {
        [
            'user_IP'    => $userIP,
            'route_name' => $routeName
        ] = $this->getRequestData();

        /** @var User $user */
        $user = $event->getUser();

        $this->logger->notice('Connexion d\'un utilisateur ', [
            'email' => $user->getEmail(),
            'adresse IP' => $userIP
        ]);
    }

    public function onLogoutEvent(LogoutEvent $event): void
    {
        [
            'user_IP'    => $userIP,
            'route_name' => $routeName
        ] = $this->getRequestData();

        /** @var User $user */
        $user = $event->getToken()->getUser();

        $this->logger->notice('Deconnexion d\'un utilisateur ', [
            'email' => $user->getEmail(),
            'adresse IP' => $userIP
        ]);
    }

    private function getRequestData(): array
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return [
                'user_IP'    => 'Inconnue',
                'route_name' => 'Inconnue'
            ];
        }

        return [
            'user_IP'    => $request->getClientIp() ?? 'Inconnue',
            'route_name' => $request->attributes->get('_route')
        ];
    }
}
