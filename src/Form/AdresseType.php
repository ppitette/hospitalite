<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('remise', TextType::class, [
                'required' => false,
                'help' => 'N° d\'appartement ou de boite à lettre - Étage - Couloir - Escalier',
            ])
            ->add('compLoc', TextType::class, [
                'required' => false,
                'help' => 'Entrée - Bâtiment - Étage - Immeuble - Résidence',
            ])
            ->add('numVoie', TextType::class, [
                'required' => false,
                'help' => 'Numéro',
            ])
            ->add('typeVoie', TextType::class, [
                'required' => false,
                'help' => 'Type de voie',
            ])
            ->add('nomVoie', TextType::class, [
                'required' => false,
                'help' => 'Nom de la voie',
            ])
            ->add('compVoie', TextType::class, [
                'required' => false,
                'help' => 'Poste Restante - BP - Lieu-dit',
            ])
            ->add('insee', TextType::class, [
                'required' => false,
                'help' => 'Code INSEE',
            ])
            ->add('cPostal', TextType::class, [
                'required' => false,
                'help' => 'Code Postal',
            ])
            ->add('commune', TextType::class, [
                'required' => false,
                'help' => 'Commune',
            ])
            ->add('pays', TextType::class, [
                'required' => false,
                'help' => 'Pays',
            ])
            ->add('diocese', TextType::class, [
                'required' => false,
                'help' => 'Diocèse',
            ])
            ->add('secteur', TextType::class, [
                'required' => false,
                'help' => 'Secteur',
            ])
            ->add('paroisse', TextType::class, [
                'required' => false,
                'help' => 'Paroisse',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
