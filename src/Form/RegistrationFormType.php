<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez-saisir un email."
                    ]),
                ]
            ])
            ->add('username', TextType::class, [
                'label'       => "Nom d'utilisateur",
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez-saisir un nom d'utilisateur."
                    ]),
                    new Length([
                        'min'        => 3,
                        'max'        => 30,
                        'minMessage' => "Votre login doit contenir au moins 3 caractères.",
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label'       => "J'accepte les conditions d'utilisation",
                'mapped'      => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Vous devez accepter les conditions.",
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label'       => "Mot de passe",
                'mapped'      => false,
                'attr'        => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez-saisir un mot de passe.',
                    ]),
                    new Length([
                        'min'        => 6,
                        'minMessage' => 'Votre mot de passe doit avoir au moins {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max'        => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
