<?php

namespace App\Form;

use App\Entity\Auction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;  
use App\Entity\Product;
use App\Repository\ProductRepository;

class AuctionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateCloture')
            ->add('dateLancement')
            ->add('prixInitial')
            ->add('idProduit')
            ->add('ajouter', SubmitType::class, ['label' => 'Ajouter']);
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auction::class,
            'id_user' => null,
        ]);
    }
}
