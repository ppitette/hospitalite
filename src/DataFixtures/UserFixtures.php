<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$email, $roles, $password, $firstName, $lastName]) {
            $user = new User();

            $jour = new \DateTimeImmutable('2021-07-29 14:24:00');

            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                $password
            ));

            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setIsEnable(true);
            $user->setRegisteredAt($jour);
            $user->setMustBeVerifiedBefore($jour->add(new \DateInterval('P1D')));
            $user->setIsVerified(true);
            $user->setVerifiedAt($jour->add(new \DateInterval('PT1H')));

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            ['ppitette@gmail.com', ['ROLE_SUPER_ADMIN'], 'chrp10%R', 'Pascal', 'Pitette'],
            ['marie.bousquet@gmail.com', ['ROLE_ADMIN'], 'Lourdes%2022', 'Marie', 'Bousquet'],
            ['yvelinepaeme27700@gmail.com', ['ROLE_USER'], 'Lourdes%2022', 'Yveline', 'Paeme'],
            ['ludo.baz1@gmail.com', ['ROLE_USER'], 'Lourdes%2022', 'Ludovic', 'Bazin'],
            ['benoit.cottereau@hnde.org', ['ROLE_USER'], 'Lourdes%2022', 'Benoit', 'Cottereau'],
            ['eric.demaegdt@wanadoo.fr', ['ROLE_USER'], 'Lourdes%2022', 'Eric', 'Demaegdt'],
            ['levesque.christophe435@orange.fr', ['ROLE_USER'], 'Lourdes%2022', 'Christophe', 'Levesque'],
            ['fam.potentier@orange.fr', ['ROLE_USER'], 'Lourdes%2022', 'Sylvie', 'Potentier'],
            ['celine.bonnet44@orange.fr', ['ROLE_USER'], 'Lourdes%2022', 'Céline', 'Bonnet'],
            ['pelerinages@evreux.catholique.fr', ['ROLE_USER'], 'Lourdes%2022', 'Service des', 'Pèlerinages'],
            ['baylot.p@gmail.com', ['ROLE_USER'], 'Lourdes%2022', 'Paul', 'Baylot'],
            ['hermine.saligue@dbmail.com', ['ROLE_USER'], 'Lourdes%2022', 'Hermine', 'Saligue'],
        ];
    }
}
