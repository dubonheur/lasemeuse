<?php

namespace App\Form;

use App\Entity\Absence;
use App\Entity\Inscription;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbsenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateab')
            ->add('motif')
            ->add('numInsc', EntityType::class, [
                'class' => Inscription::class,
                'choice_label' => 'nomComplet', // Utilisation de 'nomComplet' au lieu de 'id'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Absence::class,
        ]);
    }
}
