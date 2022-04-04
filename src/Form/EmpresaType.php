<?php

namespace App\Form;

use App\Entity\Empresa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpresaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('reu')
            ->add('telefono')
            ->add('correo')
            ->add('web')
            ->add('tipo')
            ->add('nit')
            ->add('representante')
            ->add('ci')
            ->add('estado')
            ->add('usuario')
            ->add('actoreconomico')
            ->add('representanto_email')
            ->add('representanto_movil')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Empresa::class,
        ]);
    }
}
