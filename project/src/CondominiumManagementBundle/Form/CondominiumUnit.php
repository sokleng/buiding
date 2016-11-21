<?php

namespace CondominiumManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use CondominiumManagementBundle\Constant\PaymentMethod;

class CondominiumUnit extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('room_number',
                NumberType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'type',
                EntityType::class,
                [
                    'class' => 'CondoBundle:UnitType',
                    'choice_label' => 'code',
                ]
            )
            ->add('floor')
            ->add(
                'price',
                NumberType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'payBy',
                'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
                [
                    'choices' => array_flip(PaymentMethod::getMethods()),
                ]
            )
            ->add(
                'isLocked',
                'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
                [
                    'required' => false,
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'CondoBundle\Entity\Unit',
            ]
        );
    }
}
