<?php

namespace App\Form;

use App\Entity\PersonneSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonneSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => false,
                'label' => false,
            ])
            ->add('prenom', TextType::class, [
                'required' => false,
                'label' => false,
            ])
            ->add('commune', TextType::class, [
                'required' => false,
                'label' => false,
            ])
            ->add('telephone', TextType::class, [
                'required' => false,
                'label' => false,
            ])
            ->add('decede', CheckboxType::class, [
                'required' => false,
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonneSearch::class,
            'method' => 'get',
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
