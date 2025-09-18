<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\User;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('created_at', DateTimeType::class, [
                'widget' => 'single_text',
                'disabled' => true, // Le champ est désactivé car il est géré automatiquement
            ])
            ->add('updated_at', null, [
                'widget' => 'single_text',
            ])
            ->add('totale', MoneyType::class)
            ->add('remarque', TextareaType::class, [
                'required' => false, // Le champ n'est pas obligatoire
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'En attente',
                    'En cours' => 'En cours',
                    'Terminée' => 'Terminée',
                ],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
        // Exclusion des champs spécifiés dans les options
        if (isset($options['exclude_fields'])) {
            foreach ($options['exclude_fields'] as $field) {
                $builder->remove($field);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
            'exclude_fields' => [], // Champs à exclure du formulaire

        ]);
    }
}
