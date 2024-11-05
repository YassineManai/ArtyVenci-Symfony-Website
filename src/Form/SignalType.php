<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class SignalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motif', ChoiceType::class, [
                'choices' => [
                    'Contenu inapproprié' => 'inapproprie',
                    'Spam' => 'spam',
                    'Harcèlement' => 'harcelement',
                    'Propos violents' => 'propos_violents',
                    'Discrimination' => 'discrimination',
                    'Autre' => 'autre',
                ],
                'expanded' => true,
            ]);
    }
}
