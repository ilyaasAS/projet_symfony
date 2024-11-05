<?php

namespace App\Form;

use App\Entity\Uilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Entrez votre adresse email'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'L\'email est obligatoire.',
                    ]),
                    new Assert\Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas une adresse email valide.',
                    ]),
                ],
            ])

            ->add('pseudo', TextType::class, [  
                'label' => 'Pseudo',
                'attr' => ['placeholder' => 'Choisissez un pseudo'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le pseudo est obligatoire.',
                    ]),
                ],
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => ['placeholder' => 'Entrez votre mot de passe'],
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Le mot de passe est obligatoire.',
                        ]),
                        new Assert\Length([
                            'min' => 6,
                            'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => ['placeholder' => 'Confirmez votre mot de passe'],
                ],
                'invalid_message' => 'Les mots de passe doivent correspondre.',
            ])

            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => ['placeholder' => 'Numéro de téléphone (optionnel)'],
                'required' => false,
            ])

            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Parlez un peu de vous'],
                'constraints' => [
                    new Assert\Length([
                        'max' => 500,
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
                'required' => false, // Rendre ce champ optionnel
            ]);

            // ->add('submit', SubmitType::class, [
            //     'label' => 'Enregistrer',
            //     'attr' => ['class' => 'btn btn-primary'],
            // ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Uilisateur::class,
        ]);
    }
}
