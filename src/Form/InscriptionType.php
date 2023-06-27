<?php
namespace App\Form;

use App\Entity\Inscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateInsc', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'inscription',
            ])
            ->add('frais', MoneyType::class, [
                'label' => 'Frais',
            ])
            ->add('annee', EntityType::class, [
                'class' => 'App\Entity\Annee',
                'label' => 'Année scolaire',
                'choice_label' => function ($annee) {
                    return sprintf('%s - %s', $annee->getDebutan()->format('Y'), $annee->getFinan()->format('Y'));
                },
            ])
            ->add('classe', EntityType::class, [
                'class' => 'App\Entity\Classe',
                'label' => 'Classe',
                'choice_label' => 'nomClasse',
            ])
            ->add('eleve', EntityType::class, [
                'class' => 'App\Entity\Eleve',
                'label' => 'Élève',
                'choice_label' => function ($eleve) {
                    return sprintf('%s %s', $eleve->getNom(), $eleve->getPrenom());
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
        ]);
    }
}
