<?php

namespace App\Form;

use App\Entity\Personne;
use App\Service\Parametres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonneType extends AbstractType
{
    private Parametres $parametres;

    public function __construct(Parametres $parametres)
    {
        $this->parametres = $parametres;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('genre', ChoiceType::class, [
                'choices' => $this->parametres->listCateg('genre'),
            ])
            ->add('civilite', TextType::class, [
                'required' => false,
            ])
            ->add('nom', TextType::class, [
                'required' => true,
            ])
            ->add('nomNaiss', TextType::class, [
                'required' => false,
            ])
            ->add('prenom', TextType::class, [
                'required' => false,
            ])
            ->add('telephone', TextType::class, [
                'required' => false,
            ])
            ->add('mobile', TextType::class, [
                'required' => false,
            ])
            ->add('lrCourriel', CheckboxType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('courriel', EmailType::class, [
                'required' => false,
            ])
            ->add('dateNaiss', DateType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('decede', CheckboxType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('dateDeces', DateType::class, [
                'label' => false,
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('pPele', NumberType::class)
            ->add('nbPele', NumberType::class)
            ->add('dPele', NumberType::class)
            ->add('engHosp', NumberType::class)
            ->add('engEgl', NumberType::class)
            ->add('isReferent', CheckboxType::class, [
                'required' => false,
            ])
            ->add('medical', ChoiceType::class, [
                'choices' => $this->parametres->listCateg('medical'),
            ])
            ->add('medicalAutre', TextType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
