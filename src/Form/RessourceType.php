<?php

namespace App\Form;

use App\Entity\Ressourcepedagogique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class RessourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('type')
            ->add('fichier', FileType::class, [
                'label' => 'Brochure (PDF file)',

                // non mappé signifie que ce champ n'est associé à aucune propriété d'entité
                'mapped' => false,

                // rendez-le facultatif afin que vous n'ayez pas à télécharger à nouveau le fichier PDF
                // chaque fois que vous modifiez les détails du produit
                'required' => false,

                // les champs non mappés ne peuvent pas définir leur validation à l'aide d'annotations
                 // dans l'entité associée, vous pouvez donc utiliser les classes de contraintes PHP
                'constraints' => [
                    new File([
                        'maxSize' => '5M',  // Augmenter la taille maximale du fichier à 5 Mo
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ressourcepedagogique::class,
        ]);
    }
}
