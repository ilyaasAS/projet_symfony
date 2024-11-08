<?php

namespace App\Form;

use App\Entity\ItemCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\CallbackTransformer;

class CollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ 'titre' obligatoire, min 3 et max 100 caractères
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 100,
                        'minMessage' => 'Le titre doit faire au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le titre ne doit pas dépasser {{ limit }} caractères.',
                    ])
                ]
            ])
            // Champ 'description' non obligatoire, max 500 caractères
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 500,
                        'maxMessage' => 'La description ne doit pas dépasser {{ limit }} caractères.',
                    ])
                ]
            ])
            // Champ 'isPublic' : case à cocher
            ->add('isPublic', CheckboxType::class, [
                'label' => 'Collection publique',
                'required' => false,
            ])
            // Champ 'cover' : champ de type fichier, non obligatoire
            ->add('cover', FileType::class, [
                'label' => 'Couverture de la collection (image)',
                'required' => false,
                'mapped' => false, // Si vous gérez le téléchargement de fichiers séparément
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image de type JPEG ou PNG.',
                    ])
                ]
            ])
            // Champ 'tags' : champ de texte, séparés par des virgules
            ->add('tags', TextType::class, [
                'label' => 'Tags (séparés par des virgules)',
                'required' => false,
            ])
            // Champ 'catégorie' : champ de texte, non obligatoire, max 50 caractères
            ->add('categorie', TextType::class, [
                'label' => 'Catégorie',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'La catégorie ne doit pas dépasser {{ limit }} caractères.',
                    ])
                ]
            ]);

        // Ajouter un DataTransformer pour le champ 'tags'
        $builder->get('tags')
            ->addModelTransformer(new CallbackTransformer(
                // Transforme le tableau en chaîne
                function ($tagsArray) {
                    return $tagsArray ? implode(',', $tagsArray) : '';
                },
                // Transforme la chaîne en tableau
                function ($tagsString) {
                    return $tagsString ? explode(',', $tagsString) : [];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ItemCollection::class,
        ]);
    }
}
