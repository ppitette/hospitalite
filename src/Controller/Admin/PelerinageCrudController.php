<?php

namespace App\Controller\Admin;

use App\Entity\Pelerinage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PelerinageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pelerinage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['debut' => 'DESC'])
            ->setPaginatorPageSize(15)
            ->setDateFormat('dd/MM/YYYY')
            ->setEntityLabelInSingular('Pèlerinage')
            ->setEntityLabelInPlural('Pèlerinages');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('cle', 'Clé');

        yield TextField::new('libelle', 'Libellé');

        yield TextField::new('abrege', 'Abrégé');

        yield DateField::new('debut', 'Début');

        yield DateField::new('fin', 'Fin');

        yield TextField::new('theme', 'Thème');

        yield TextEditorField::new('remarque', 'Informations')
            ->onlyOnForms();
    }
}
