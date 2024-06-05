<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du produit',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez le nom du produit',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le nom du produit']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le nom du produit ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('ingredients', TextareaType::class, [
                'label' => 'Ingrédients (séparés par des virgules)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez les ingrédients du produit (séparés par des virgules)',
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image ou vidéo du produit',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => 'image/*,video/*',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'Le fichier ne doit pas dépasser 1024 Ko',
                        'mimeTypes' => [
                            'image/*', // Accepter uniquement les fichiers d'image
                            'video/*', // Accepter uniquement les fichiers vidéo
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image ou une vidéo valide',
                    ]),
                ],
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix',
                'required' => true,
                'html5' => true,
                'attr' => [
                    'placeholder' => 'Entrez le prix en €',
                    'step' => '0.01', // Affichez les prix avec deux décimales
                ],
                'invalid_message' => 'Veuillez saisir un prix valide',
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom', // Affichez le nom de la catégorie au lieu de l'ID
                'label' => 'Catégorie du produit',
                'required' => true,
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
