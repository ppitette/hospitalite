<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @method User getUser
 */
class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private EntityRepository $entityRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDateFormat('dd/MM/YYYY')
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', '#')->hideOnForm();

        yield TextField::new('lastname', 'Nom');

        yield TextField::new('firstname', 'Prénom');

        yield EmailField::new('email');

        yield ChoiceField::new('roles')
            ->allowMultipleChoices()
            ->renderAsBadges([
                'ROLE_USER' => 'info',
                'ROLE_LECT' => 'success',
                'ROLE_ADMIN' => 'warning',
                'ROLE_SUPER_ADMIN' => 'danger',
            ])
            ->setChoices([
                'Standard' => 'ROLE_USER',
                'Standard +' => 'ROLE_LECT',
                'Administrateur' => 'ROLE_ADMIN',
                'Super Administrateur' => 'ROLE_SUPER_ADMIN',
            ])
        ;

        yield TextField::new('password')
            ->onlyOnForms()
            ->setFormType(PasswordType::class)
        ;

        yield BooleanField::new('isEnable', 'Autorisé');

        yield BooleanField::new('isVerified', 'Vérifié');

        yield DateField::new('registeredAt', 'Inscription');
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var User $user */
        $user = $entityInstance;

        $plainPassword = $user->getPassword();
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);

        $user->setPassword($hashedPassword);

        parent::persistEntity($entityManager, $user);
    }
}
