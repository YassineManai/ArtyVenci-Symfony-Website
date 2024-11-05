<?php

namespace App\Form;

use App\Entity\Workshop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class WorkshopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('details')
            ->add('image', FileType::class, [
                'label' => 'Image (JPG file)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        
                        'mimeTypesMessage' => 'Please upload a valid JPG document',
                    ]),
                    new NotBlank([
                        'message'=>'Please select an image',
                    ])
                ],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Workshop::class,
        ]);
    }
}
