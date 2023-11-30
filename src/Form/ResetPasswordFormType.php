<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email', EmailType::class,
                [
                    'attr' => ['value' => ""]
                ]
            )
            ->add(
                'plainPassword', RepeatedType::class,
                [
                    'type'            => PasswordType::class,
                    'invalid_message' => "La confirmation du mot de passe ne correspond pas.",
                    'first_options'   => ["label" => "Mot de passe"],
                    'second_options'  => ["label" => "Confirmer le mot de passe"],
                    'attr'            => ['autocomplete' => 'new-password'],
                ]
            )
            ->add(
                'resetPassword', SubmitType::class, [
                'label' => 'Réinitialiser mon mot de passe'
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
            'data_class' => User::class,
            ]
        );
    }
}
