<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class SignupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('password')
            ->add('role')
            ->add('firstname')
            ->add('lastname')
            ->add('adress')
            ->add('phone')
            ->add('gender')
            ->add('dob')
            ->add('imageurl', FileType::class, [
                'label' => 'Product Image',
                'mapped' => false, // This field is not mapped to any entity property
                'required' => false, // Set to true if the file is required
            ])
            ->add('Done',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
