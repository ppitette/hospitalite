<?php

namespace App\Form;

use App\Entity\Inscription;
use App\Service\Parametres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    private Parametres $parametres;

    public function __construct(Parametres $parametres)
    {
        $this->parametres = $parametres;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numInsc', IntegerType::class, [
                'required' => true,
            ])
            ->add('inscritAt', DateType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('entite', ChoiceType::class, [
                'choices' => $this->parametres->listCateg('entite'),
            ])
            ->add('couple', CheckboxType::class, [
                'required' => false,
            ])
            ->add('conjoint', TextType::class, [
                'required' => false,
            ])
            ->add('horsEffectif', CheckboxType::class, [
                'required' => false,
            ])
            ->add('nouveau', CheckboxType::class, [
                'required' => false,
            ])
            ->add('voyAller', CheckboxType::class, [
                'required' => false,
            ])
            ->add('voyRetour', CheckboxType::class, [
                'required' => false,
            ])
            ->add('hebHosp', CheckboxType::class, [
                'required' => false,
            ])
            ->add('hebSingle', CheckboxType::class, [
                'required' => false,
            ])
            ->add('hebPerso', TextType::class, [
                'required' => false,
            ])
            ->add('listeAtt', CheckboxType::class, [
                'required' => false,
            ])
            ->add('personneUrgence', TextType::class, [
                'required' => false,
            ])
            ->add('situation', ChoiceType::class, [
                'choices' => $this->parametres->listCateg('situat'),
            ])
            ->add('connuHosp', ChoiceType::class, [
                'choices' => $this->parametres->listCateg('connu'),
            ])
            ->add('connuHospQui', TextType::class, [
                'required' => false,
            ])
            ->add('prefHeberg', ChoiceType::class, [
                'choices' => $this->parametres->listCateg('hotel'),
            ])
            ->add('partageChambre', ChoiceType::class, [
                'choices' => $this->parametres->listCateg('partch'),
            ])
            ->add('partageChambreNom', TextType::class, [
                'required' => false,
            ])
            ->add('serviceChambre', ChoiceType::class, [
                'choices' => $this->parametres->listCateg('chserv'),
            ])
            ->add('porte', CheckboxType::class, [
                'required' => false,
            ])
            ->add('voiture', CheckboxType::class, [
                'required' => false,
            ])
            ->add('gardeNuit', CheckboxType::class, [
                'required' => false,
            ])
            ->add('piscine', CheckboxType::class, [
                'required' => false,
            ])
            ->add('animation', CheckboxType::class, [
                'required' => false,
            ])
            ->add('instrument', TextType::class, [
                'required' => false,
            ])
            ->add('tenue', CheckboxType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
        ]);
    }
}
