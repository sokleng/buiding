<?php

namespace ProjectRealtyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ProjectRealtyBundle\Constant\PaymentStatus;

class ProjectPaymentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contact')
            ->add(
                'unit',
                'Symfony\Bridge\Doctrine\Form\Type\EntityType',
                [
                    'required' => true,
                    'query_builder' => $options['units'],
                    'class' => 'ProjectRealtyBundle\Entity\ProjectUnit',
                ]
            )
            ->add('paymentMethod')
            ->add('receiver')
            ->add('amount')
            ->add(
                'received',
                'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
                [
                    'required' => false,
                ]
            )
            ->add(
                'status',
                'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
                [
                    'choices' => array_flip(PaymentStatus::getStatuses()),
                    'required' => false,
                ]
            )
            ->add('description');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'ProjectRealtyBundle\Entity\ProjectPayment',
                'units' => null,
            ]
        );
    }
}
