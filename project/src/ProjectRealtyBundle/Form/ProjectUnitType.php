<?php

namespace ProjectRealtyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProjectUnitType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('room_number',
                'Symfony\Component\Form\Extension\Core\Type\NumberType',
                [
                    'required' => true,
                ]
            )
            ->add(
                'type',
                EntityType::class,
                [
                    'class' => 'ProjectRealtyBundle:ProjectUnitType',
                    'choice_label' => 'code',
                ]
            )
            ->add('floor')
            ->add(
                'price',
                'Symfony\Component\Form\Extension\Core\Type\NumberType',
                [
                    'required' => true,
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProjectRealtyBundle\Entity\ProjectUnit',
        ));
    }
}
