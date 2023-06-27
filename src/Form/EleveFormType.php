<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\File;






class EleveFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')

            ->add('prenom')
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'M' => 'Masculin',
                    'F' => 'Féminin',
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('datenaissance')

            
            ->add('photo', FileType::class,[
                "required" =>false,
                "attr" =>[
                    "class" => "custom-file-input",
                    "id" =>"validatedCustomFile"
                ],
                "constraints" => new File([
                    "maxSize"=>"10M",
                    "mimeTypes" => [
                        "image/jpeg",
                        "image/jpg",
                        "image/png",
                    ],
                    "mimeTypesMessage" => "Seules les images JPEG, JPG et PNG sont autorisées"
                ]),
                'data_class' => null
            ])         
           
            ->add('parent', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
                'attr' => [
                    'class' => 'form-control'
                ],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_PARENT"%');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}