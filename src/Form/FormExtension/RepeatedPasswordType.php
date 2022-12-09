<?php

namespace App\Form\FormExtension;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepeatedPasswordType extends AbstractType
{
    public function getParent(): string
    {
        return RepeatedType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe saisis ne correspondent pas.',
            'required' => true,
            'first_options' => [
                'label' => 'Mot de passe',
                'label_attr' => [
                    'title' => 'Votre mot de passe doit contenir au moins 8 caractères dont 1 majuscule, 1 minuscule, 1 chiffre et un caractère spécial (dans un ordre aléatoire).',
                ],
                'attr' => [
                    'pattern' => '^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{8,}$',
                    'title' => 'Votre mot de passe doit contenir au moins 8 caractères dont 1 majuscule, 1 minuscule, 1 chiffre et un caractère spécial (dans un ordre aléatoire).',
                    'maxlength' => 255,
                ],
            ],
            'second_options' => [
                'label' => 'Confirmer le mot de passe.',
                'label_attr' => [
                    'title' => 'Confirmez votre mot de passe.',
                ],
                'attr' => [
                    'pattern' => '^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{8,}$',
                    'title' => 'Confirmez votre mot de passe.',
                    'maxlength' => 255,
                ],
            ],
        ]);
    }
}
