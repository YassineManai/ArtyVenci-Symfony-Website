<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eName')
            ->add('place')
            ->add('eDate')
            ->add('ticketPrice')
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
            ->add('mapCoordinates', HiddenType::class, [
                'mapped' => false, // This field won't map to an entity property
                'required' => false, // It will be populated by JavaScript
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
