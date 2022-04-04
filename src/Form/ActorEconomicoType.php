<?php

namespace App\Form;

use App\Entity\ActorEconomico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActorEconomicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codigo')
            ->add('nombre')
            ->add('direccion')
            ->add('siglas')
            ->add('descripcion')
            ->add('forma')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActorEconomico::class,
        ]);
    }
}
