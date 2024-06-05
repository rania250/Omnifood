<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
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
            ->add('couleur', ColorType::class, [
                'label' => 'Couleur de la catégorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
