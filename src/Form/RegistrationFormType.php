<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email', EmailType::class
            )
            ->add(
                'username', TextType::class,
                [
                    'label' => "Nom d'utilisateur",
                ]
            )
            ->add(
                'agreeTerms', CheckboxType::class,
                [
                'label'       => "J'accepte les conditions d'utilisation",
                'mapped'      => false,
                'constraints' =>
                    [
                        new IsTrue(
                            [
                            'message' => "Vous devez accepter les conditions.",
                            ]),
                    ],
                ]
            )
            ->add(
                'plainPassword', RepeatedType::class,
                [
                    // Instead of being set onto the object directly,
                    // This is read and encoded in the controller
                    'type'            => PasswordType::class,
                    'invalid_message' => "La confirmation du mot de passe ne correspond pas.",
                    'first_options'   => ["label" => "Mot de passe"],
                    'second_options'  => ["label" => "Confirmer le mot de passe"],
                    'attr'            => ['autocomplete' => 'new-password'],
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
