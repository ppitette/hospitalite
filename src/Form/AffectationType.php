<?php

namespace App\Form;

use App\Entity\Inscription;
use App\Service\Parametres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffectationType extends AbstractType
{
    private Parametres $parametres;

    public function __construct(Parametres $parametres)
    {
        $this->parametres = $parametres;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('groupe', ChoiceType::class, [
                'choices' => $this->parametres->listCateg('groupe'),
            ])
            ->add('hebHosp', CheckboxType::class, [
                'required' => false,
            ])
            ->add('hebSingle', CheckboxType::class, [
                'required' => false,
            ])
            ->add('hebHotel', ChoiceType::class, [
                'choices' => $this->parametres->listCateg('hotel'),
            ])
            ->add('hebChambre', TextType::class, [
                'required' => false,
            ])
            ->add('hebPerso', TextType::class, [
                'required' => false,
            ])
            ->add('trnsCar', ChoiceType::class, [
                'choices' => $this->parametres->listCateg('transp'),
            ])
            ->add('trnsPlace', TextType::class, [
                'required' => false,
            ])
            ->add('trnsSiege', ChoiceType::class, [
                'choices' => $this->parametres->listCateg('siege'),
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
