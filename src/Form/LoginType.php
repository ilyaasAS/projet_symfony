<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                'attr' => ['placeholder' => 'Entrez votre adresse email'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est obligatoire.']),
                    new Assert\Email(['message' => 'L\'adresse email "{{ value }}" n\'est pas une adresse email valide.']),
                ],
            ])

            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['placeholder' => 'Entrez votre mot de passe'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le mot de passe est obligatoire.']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ]);

            // ->add('submit', SubmitType::class, [
            //     'label' => 'Se connecter', 
            //     'attr' => ['class' => 'btn btn-primary'],
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
