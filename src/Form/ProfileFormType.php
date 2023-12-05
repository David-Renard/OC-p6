<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserPicture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,
                [
                    'label'       => "Nouveau nom d'utilisateur",
                    'required'    => false,
                    'constraints' =>
                        [
                            new Length(
                                min: 6,
                                minMessage: "Votre nom d'utilisateur doit avoir au moins {{ limit }} caractères.",
                                max: 50,
                                maxMessage: "Votre nom d'utilisateur doit avoir au plus {{ limit }} caractères.",
                            ),
                        ]
                ]
            )
            ->add('userPicture', FileType::class,
                [
                    'label'       => 'Nouvel avatar',
                    'mapped'      => false,
                    'required'    => false,
                    'constraints' =>
                        [
                            new File(
                                [
                                    'maxSize' => '5Mi',
                                    'maxSizeMessage' => "Le fichier {{ file }} est trop grand ({{ size }}{{ suffix }}). {{ limit }}{{ suffix }} maximum autorisé.",
                                    'extensions' =>
                                        [
                                            'jpeg',
                                            'jpg',
                                            'png',
                                        ],
                                    'extensionsMessage' => "Seuls les fichiers 'jpeg', 'jpg' et 'png' sont autorisés.",
                                ]
                            )
                        ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
