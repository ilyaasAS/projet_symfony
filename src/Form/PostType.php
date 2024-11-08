<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new Length(['min' => 3, 'max' => 100]),
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'constraints' => [
                    new Length(['max' => 1000]),
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (optionnelle)',
                'required' => false,
                'mapped' => false, // Champ de fichier non lié directement à une propriété
            ])
            ->add('isPublic', CheckboxType::class, [
                'label' => 'Public',
                'required' => false,
                'data' => false, // Par défaut non coché
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class, // L'entité associée
        ]);
    }
}