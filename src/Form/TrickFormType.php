<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class TrickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name', TextType::class, [
                'label' => 'Nom de la figure',
                'attr'  => [
                    'class' => 'form-label-extended'
                ]
            ]
            )
            ->add(
                'description', TextareaType::class, [
                'attr' => [
                    'rows' => 5,
                    'cols' => 70,
                ],
            ]
            )
            ->add(
                'category', EntityType::class, [
                'class'        => TrickCategory::class,
                'label'        => 'Catégorie',
                'choice_label' => 'name',
                'expanded'     => false,
                'multiple'     => false,
            ]
            )
            ->add(
                'author', CheckboxType::class, [
                'label'    => "M'attribuer cette figure",
                'mapped'   => false,
                'required' => false,
            ]
            )
            ->add('attachment', FileType::class, [
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'label' => "Ajouter une ou plusieurs image(s)",
                'constraints' => [
                    new All([
                        'constraints' =>
                            new File([
                                'maxSize' => '5Mi',
                                'maxSizeMessage' => "Le fichier {{ file }} est trop grand ({{ size }}{{ suffix }}). {{ limit }}{{ suffix }} maximum autorisé.",
                                'mimeTypes' => ['images/*'],
                            ]),
                        ],
                    )
                ],
            ])
            ->add('video', CollectionType::class, [
                'entry_type' => VideoFormType::class,
                'label' => false,
                'allow_add'  => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add(
                'submit', SubmitType::class, [
                'label' => "Valider"
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
            'data_class' => Trick::class,
            ]
        );
    }
}
